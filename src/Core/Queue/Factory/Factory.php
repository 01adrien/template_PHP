<?php

namespace Src\Core\Queue\Factory;

use Exception;
use Src\Core\Abstracted\AbstractFactory;
use Src\Core\Interfaces\QueueInterface;

class Factory extends AbstractFactory
{

    public function make(array $config): QueueInterface
    {
        if (!isset($config['type'])) {
            throw new Exception('type is not defined');
        }

        $type = $config['type'];

        if (isset($this->drivers[$type])) {
            return $this->drivers[$type]($config);
        }

        throw new Exception('unrecognised type');
    }
}
