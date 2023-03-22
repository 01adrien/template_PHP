<?php

namespace Src\Core\Attributes\Database;

use Attribute;

#[Attribute]
class Field
{
  public function __construct(
    public string $name,
    public string $alias
  ) {
  }
}
