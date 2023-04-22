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
      /*
      if (substr_count($option, '.')) {
      $segments = explode('.', $option);
      return $this->getDots($this->config[$segments[0]], $segments);
      }
      */
        return $this->config[$option] ?? [];
    }

    public function getDots(array $base, array $segments): mixed
    {
        $current = $base;
        if (count($segments) === 2) {
            return $current[$segments[1]];
        }
        array_shift($segments);
        return $this->getDots($current[$segments[0]], $segments);
    }
}