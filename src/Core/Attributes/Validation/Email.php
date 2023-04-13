<?php

namespace Src\Core\Attributes\Validation;

use Attribute;
use Src\Core\Abstracted\Entity;

#[Attribute()]
class Email
{
  public function isValid(Entity $entity, string $prop): bool | array
  {
    if (!filter_var($entity->$prop, FILTER_VALIDATE_EMAIL)) {
      return [
        'type' => $prop,
        'message' => "$prop not valid"
      ];
    }
    return true;
  }
}
