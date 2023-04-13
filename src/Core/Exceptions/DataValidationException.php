<?php

namespace Src\Core\Exceptions;

use Throwable;

class DataValidationException extends \RuntimeException
{
  public function __construct(
    public readonly array $errors,
    string $message = 'invalid user input',
    int $code = 422,
    ?Throwable $previous = null
  ) {
    parent::__construct($message, $code, $previous);
  }
}
