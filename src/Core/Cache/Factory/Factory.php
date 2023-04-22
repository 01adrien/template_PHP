<?php

namespace Src\Core\Cache\Factory;

use Exception;
use Src\Core\Abstracted\AbstractFactory;
use Src\Core\Interfaces\CacheInterface;

class Factory extends AbstractFactory
{

    public function make(array $config): CacheInterface
    {
        if (!isset($config['type'])) {
            throw new Exception('cache type is not defined');
        }

        $type = $config['type'];

        if (isset($this->drivers[$type])) {
            return $this->drivers[$type]($config);
        }

        throw new Exception('unrecognised type cache');
    }
}
