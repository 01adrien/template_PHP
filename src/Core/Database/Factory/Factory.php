<?php

namespace Src\Core\Database\Factory;


use Exception;
use Src\Core\Abstracted\AbstractFactory;
use Src\Core\Interfaces\ConnectionInterface;

class Factory extends AbstractFactory
{
  public function make(array $config): ConnectionInterface
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
