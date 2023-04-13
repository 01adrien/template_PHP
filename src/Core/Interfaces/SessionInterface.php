<?php

namespace Src\Core\Interfaces;

interface SessionInterface
{
  public function start(): void;
  public function save(): void;
  public function isActive(): bool;
  public function forget(string $key): void;
  public function put(string $key, mixed $value): bool;
  public function regenerateID(): void;
  public function get(string $key, mixed $default = null): mixed;
  public function has(string $key): bool;
}
