<?php

namespace Src\Core\Queue\QueueSystem;

use Psr\Container\ContainerInterface;
use Src\App\Entities\Job;
use Src\App\Models\JobModel;
use Src\Core\Interfaces\QueueInterface;

class DatabaseQueue implements QueueInterface
{

    private JobModel $jobModel;
    public function __construct(
        private array $config,
        private ContainerInterface $container
    ) {
        $this->jobModel = $this->container->get(JobModel::class);
    }

    public function push(Job $job, string $queue): void
    {
        if ($job->id) {
            $this->jobModel->update($job->id, (array)$job);
            return;
        }
        $job->setQueue($queue);
        $this->jobModel->insert((array)$job);
    }

    public function shift(string $queue): Job | bool
    {
        return $this->jobModel->pickFirstJob(
            [
            'attempts' => $this->config['attempts'],
            'queue' => $queue
            ]
        );
    }

    public function isEmpty(string $queue): bool
    {
        return $this->jobModel->count(
            ['queue' => $queue, 'attempts' => $this->config['attempts']]
        ) === 0;
    }

    public function getConfig(): array
    {
        return $this->config;
    }
}
