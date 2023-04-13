<?php

namespace Src\Core\Attributes\Validation;

use Attribute;
use Src\Core\Abstracted\Entity;

#[Attribute()]
class Name
{
  public function isValid(Entity $entity, string $prop): bool | array
  {
    if (!preg_match("/^[a-zA-Z-' ]*$/", $entity->$prop)) {
      return [
        'type' => $prop,
        'message' => "$prop not valid, only letters are allowed"
      ];
    }
    return true;
  }
}
