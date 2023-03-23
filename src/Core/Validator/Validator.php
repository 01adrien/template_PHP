<?php

namespace Src\Core\Validator;

use Src\Core\Abstracted\Entity;
use Src\Core\Attributes\Validation\{
  Email,
  Length,
  Required
};

class Validator
{

  public function __construct(private ErrorsObject $errors)
  {
  }
  public function validateLength(Entity $entity, \ReflectionProperty $property): self
  {
    $prop = $property->getName();
    if (!$entity->$prop) return $this;
    $attributes = $property->getAttributes(
      Length::class,
      \ReflectionAttribute::IS_INSTANCEOF
    );
    foreach ($attributes as $attr) {
      $length = $attr->newInstance();
      if (
        strlen($entity->$prop) < $length->min ||
        strlen($entity->$prop) > $length->max
      ) $this->errors->push(
        $prop,
        "$prop length should be between $length->min & $length->max"
      );
    }
    return $this;
  }

  public function isRequired(Entity $entity, \ReflectionProperty $property): self
  {
    $prop = $property->getName();
    $attributes = $property->getAttributes(
      Required::class,
      \ReflectionAttribute::IS_INSTANCEOF
    );
    foreach ($attributes as $attr) {
      if (!$entity->$prop) {
        $this->errors->push(
          $prop,
          "$prop field is required"
        );
      }
    }
    return $this;
  }

  public function validateEmail(Entity $entity, \ReflectionProperty $property): self
  {
    $prop = $property->getName();
    $attributes = $property->getAttributes(
      Email::class,
      \ReflectionAttribute::IS_INSTANCEOF
    );
    foreach ($attributes as $attr) {
      if (!filter_var($entity->$prop, FILTER_VALIDATE_EMAIL)) {
        $this->errors->push($prop, "$prop not valid");
      }
    }
    return $this;
  }

  public function getErrors(): ErrorsObject
  {
    return $this->errors;
  }
}
