<?php

namespace Src\Core\Attributes\Routes;

use Attribute;

#[Attribute()]
class Base
{
  public function __construct(public string $basePath)
  {
  }
}
