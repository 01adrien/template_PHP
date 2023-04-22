<?php

namespace Src\Core\Log\Factory;

use Exception;
use Psr\Log\LoggerInterface;
use Src\Core\Config\Config;
use Src\Core\Enums\LoggerSystem;
use Src\Core\Log\LogSystem\MonologLogger;

class LoggerFactory
{
    public function __invoke(Config $config, Factory $factory): LoggerInterface
    {
        switch ($config->get('logger.default')) {
            case LoggerSystem::Stream:
                if (!$factory->hasDriver(LoggerSystem::Stream->value)) {
                    $factory->addDriver(
                        'stream',
                        fn (array $config) => new MonologLogger($config)
                    );
                }
                return $factory->make($config->get('logger.stream'));

            default:
                throw new Exception('you must choose default logger system on config file');
        }
    }
}
