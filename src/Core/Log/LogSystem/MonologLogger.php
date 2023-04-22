<?php

namespace Src\Core\Log\LogSystem;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Psr\Log\LoggerInterface;

class MonologLogger extends Logger implements LoggerInterface
{
    private ?Logger $logger = null;
    public function __construct(private array $config)
    {
    }

    public function getLogger()
    {
        if (!isset($this->logger)) {
            $this->logger = new Logger($this->config['name']);
            $this->logger->pushHandler(new StreamHandler($this->config['path'], $this->config['minimum']));
        }

        return $this->logger;
    }
}
