<?php

namespace Src\Core\Queue\Factory;

use Exception;
use Psr\Container\ContainerInterface;
use Src\Core\Config\Config;
use Src\Core\Enums\QueueSystem;
use Src\Core\Interfaces\QueueInterface;
use Src\Core\Queue\QueueSystem\DatabaseQueue;
use Src\Core\Queue\QueueSystem\RedisQueue;

class QueueFactory
{
    public function __invoke(
        Factory $factory,
        Config $config,
        ContainerInterface $container
    ): QueueInterface {

        switch ($config->get('queue.default')) {
            case QueueSystem::Database:
                if (!$factory->hasDriver(QueueSystem::Database->value)) {
                    $factory->addDriver(
                        'database',
                        fn (array $config) => new DatabaseQueue($config, $container)
                    );
                }
                return $factory->make($config->get('queue.database'));

            case QueueSystem::Redis:
                if (!$factory->hasDriver(QueueSystem::Redis->value)) {
                    $factory->addDriver(
                        'redis',
                        fn (array $config) => new RedisQueue($config, $container)
                    );
                }
                return $factory->make($config->get('queue.redis'));

            default:
                throw new Exception('you must choose default queue system in config file');
        }
    }
}
