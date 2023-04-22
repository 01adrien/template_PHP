<?php

namespace Src\Core\Validator;

class ErrorsObject
{
  private array $errors = [];

  public function push(string $property, string $message): void
  {
    $this->errors[$property] = $message;
  }

  public function getErrors(): array
  {
    return $this->errors;
  }

  public function deleteErrors(): void
  {
    $this->errors = [];
  }
}