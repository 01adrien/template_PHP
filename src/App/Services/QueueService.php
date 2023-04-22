<?php

namespace Src\App\Services;

use Closure;
use Exception;
use Psr\Log\LoggerInterface;
use Src\App\Entities\Job;
use Src\App\Models\JobModel;
use Src\Core\Enums\QueueSystem;
use Src\Core\Exceptions\EmptyQueueException;
use Src\Core\Interfaces\QueueInterface;
use Src\Core\Queue\Factory\JobFactory;

class QueueService
{

    private string $queueSystem;

    private int $maxAttempts;

    public function __construct(
        private QueueInterface $queue,
        private LoggerInterface $logger,
        private JobFactory $jobFactory,
        private JobModel $jobModel
    ) {
        $this->queueSystem = $this->queue->getConfig()['type'];
        $this->maxAttempts = $this->queue->getConfig()['attempts'];
    }

    public function enqueue(string $queue, Job $job): static
    {
        $this->queue->push($job, $queue);
        return $this;
    }

    public function dequeue(string $queue): Job
    {
        $job = $this->queue->shift($queue);
        if (!$job) {
            throw new EmptyQueueException(['msg' => 'empty queue']);
        }
        return $job;
    }

    public function isEmpty(string $queue): bool
    {
        return $this->queue->isEmpty($queue);
    }

    public function handleProcessing(string $queue): int
    {
        $errors = 0;
        while (!$this->isEmpty($queue)) {
          /** @var Job $job */
            $job = $this->dequeue($queue);
            try {
                $job->run();
                $this->updateQueue($job);
                $this->logger->info(
                    "job n° {$job->uniqid} on {$job->queue} " .
                    "queue, attempt {$job->attempts} suceed"
                );
                sleep(1);
            } catch (Exception $e) {
                if ($job->attempts < $this->maxAttempts) {
                    $job->setAttempts($job->attempts + 1);
                    $this->enqueue($queue, $job);
                    $this->logger->error(
                        "job n° {$job->uniqid} on {$job->queue} " .
                        "queue, attempt {$job->attempts} fail : " .
                        " {$e->getmessage()}"
                    );
                    if ($job->attempts === 2) {
                            $this->logger->error(
                                "job n° {$job->uniqid} on {$job->queue} deleted"
                            );
                    }
                    sleep(1);
                    $errors++;
                }
            }
        }
        return $errors;
    }


    public function createJob(Closure $closure, array $params): Job
    {
        return $this->jobFactory->make($closure, $params);
    }

    private function updateQueue(Job $job): void
    {
        if ($this->queueSystem === QueueSystem::Database->value) {
            $job->setIsComplete(1);
            $this->jobModel->update($job->id, (array)$job);
        }
        return;
    }
}