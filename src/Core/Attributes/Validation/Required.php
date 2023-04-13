<?php

namespace Src\Core\Attributes\Validation;

use Attribute;
use Src\Core\Abstracted\Entity;

#[Attribute()]

class Required
{
  public function isValid(Entity $entity, string $prop): bool | array
  {
    if (!$entity->$prop) {
      return [
        'type' => $prop,
        'message' => "$prop field is required"
      ];
    }
    return true;
  }
}
