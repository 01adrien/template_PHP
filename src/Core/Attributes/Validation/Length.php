<?php

namespace Src\Core\Attributes\Validation;

use Attribute;

#[Attribute()]

class Length
{
  public function __construct(
    public int $min,
    public int $max
  ) {
  }
}
