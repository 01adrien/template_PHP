<?php

namespace Src\Core\Interfaces;

interface CacheInterface
{
  public function has(string $key): bool;
  public function get(string $key, mixed $default = null): mixed;
  public function put(string $key, mixed $value, int $seconds = null): static;
  public function forget(string $key): static;
  public function flush(): static;
}
