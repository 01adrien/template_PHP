<?php

namespace Src\Core\Attributes\Routes;

use Attribute;

#[Attribute]
class Route
{
  public function __construct(
    public string $name,
    public string $path,
    public string $method
  ) {
  }
}
