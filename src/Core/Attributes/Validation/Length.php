<?php

namespace Src\Core\Attributes\Validation;

use Attribute;
use Src\Core\Abstracted\Entity;

#[Attribute()]

class Length
{
  public function __construct(
    public int $min,
    public int $max
  ) {
  }
  public function isValid(Entity $entity, string $prop): bool | array
  {
    if (
      strlen($entity->$prop) < $this->min ||
      strlen($entity->$prop) > $this->max
    ) {
      return [
        'type' => $prop,
        'message' => "$prop length should be between $this->min & $this->max"
      ];
    }
    return true;
  }
}
