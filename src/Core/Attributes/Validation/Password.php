<?php

namespace Src\Core\Attributes\Validation;

use Attribute;
use Src\Core\Abstracted\Entity;

#[Attribute()]
class Password
{
  public function isValid(Entity $entity, string $prop): bool | array
  {
    $passwordRegex = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$/";
    if (!preg_match($passwordRegex, $entity->$prop)) {
      return [
        'type' => $prop,
        'message' => "$prop not valid."
      ];
    }
    return true;
  }
}
