<?php

namespace Src\Core\Attributes\Validation;

use Attribute;
use Src\Core\Abstracted\Entity;

#[Attribute()]
class Number
{
  public function isValid(Entity $entity, string $prop): bool | array
  {
    if (!(int)$entity->$prop) {
      return [
        'type' => $prop,
        'message' => "$prop invalid"
      ];
    }
    return true;
  }
}
