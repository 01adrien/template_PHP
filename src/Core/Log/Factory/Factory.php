<?php

namespace Src\Core\Log\Factory;

use Exception;
use Psr\Log\LoggerInterface;
use Src\Core\Abstracted\AbstractFactory;

class Factory extends AbstractFactory
{
    public function make(array $config): LoggerInterface
    {
        if (!isset($config['type'])) {
            throw new Exception('type is not defined');
        }

        $type = $config['type'];

        if (isset($this->drivers[$type])) {
            return $this->drivers[$type]($config)->getLogger();
        }

        throw new Exception('unrecognised type');
    }
}
