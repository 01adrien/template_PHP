<?php

namespace Src\Core\Attributes\Validation;

use Attribute;
use Src\Core\Abstracted\Entity;
use Src\Core\Abstracted\Model;

#[Attribute()]

class Unique
{
  public function __construct()
  {
  }
  public function isValid(Entity $entity, string $prop, Model $model, string $field): bool | array
  {
    $exists = $model->findBy($field, $entity->$prop);
    if ($exists) {
      return [
        'type' => 'exists',
        'message' => "$prop already exists"
      ];
    }
    return true;
  }
}
