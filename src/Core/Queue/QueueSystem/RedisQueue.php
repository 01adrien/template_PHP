<?php

namespace Src\Core\Queue\QueueSystem;

use Src\App\Entities\Job;
use Src\Core\Interfaces\QueueInterface;
use Predis\Client;
use Psr\Container\ContainerInterface;

class RedisQueue implements QueueInterface
{

    private Client $redisClient;

    public function __construct(
        private array $config,
        private ContainerInterface $container
    ) {
        $this->redisClient = new Client(
            "{$this->config['host']}:{$this->config['port']}"
        );
        $this->redisClient->auth($this->config['pass']);
        $this->redisClient->select($this->config['database']);
    }
    public function push(Job $job, string $queue): void
    {
        $job->setQueue($queue);
        $this->redisClient->lpush($queue, [serialize($job)]);
    }

    public function shift(string $queue): Job | bool
    {
        $job = $this->redisClient->lpop($queue);
        return $job ? unserialize($job) : false;
    }

    public function isEmpty(string $queue): bool
    {
        return $this->redisClient->llen($queue) === 0;
    }

    public function getConfig(): array
    {
        return $this->config;
    }
}
