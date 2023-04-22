<?php

namespace Src\Core\Storage\Factory;

use Exception;
use Src\Core\Abstracted\AbstractFactory;
use Src\Core\Interfaces\StorageInterface;

class Factory extends AbstractFactory
{
    public function make(array $config): StorageInterface
    {
        if (!isset($config['type'])) {
            throw new Exception('type is not defined');
        }

        $type = $config['type'];

        if (isset($this->drivers[$type])) {
            return $this->drivers[$type]($config)->getStorage();
        }

        throw new Exception('unrecognised type');
    }
}
