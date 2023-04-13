<?php

namespace Src\Core\Config;

class Config
{
  private array $config = [];

  public function __construct(private string $configPath)
  {
    $this->config = require $configPath;
  }
  public function get(string $option): mixed
  {
    if (str_contains($option, '.')) {
      [$type, $name] = explode('.', $option);
      return $this->config[$type][$name] ?? '';
    }
    return $this->config[$option] ?? [];
  }
}
