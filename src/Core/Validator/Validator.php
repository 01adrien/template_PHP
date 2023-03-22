<?php

namespace Src\Core\Validator;

use Src\Core\Abstracted\Entity;
use Src\Core\Attributes\Validation\Length;

class Validator
{

  public function __construct(private ErrorsObject $errors)
  {
  }
  public function validateLength(Entity $entity, Length $length, string $property): void
  {
    if (
      strlen($entity->$property) < $length->min ||
      strlen($entity->$property) > $length->max
    ) $this->errors->push($property, "length should be beween $length->min & $length->max");
  }

  public function getErrors(): ErrorsObject
  {
    return $this->errors;
  }
}
