<?php

namespace Src\Core\Exceptions;

class CsrfInvalidException  extends \RuntimeException
{
  public function __construct(
    public readonly array $errors,
    string $message = 'invalid csrf token',
    int $code = 422,
    ?\Throwable $previous = null
  ) {
    parent::__construct($message, $code, $previous);
  }
}
