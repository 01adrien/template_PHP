<?php

namespace Src\Core\Abstracted;

use Closure;

abstract class AbstractFactory
{
  protected array $drivers = [];

  public function addDriver(string $name, Closure $driver): static
  {
    $this->drivers[$name] = $driver;
    return $this;
  }

  public abstract function make(array $config);

  public function hasDriver(string $name): bool
  {
    if (array_key_exists($name, $this->drivers)) {
      return true;
    }
    return false;
  }
}
