<?php

namespace Src\Core\Interfaces;

interface StackInterface
{
  public function push(mixed $data): self;
  public function pop(): mixed;
  public function length(): int;
}
