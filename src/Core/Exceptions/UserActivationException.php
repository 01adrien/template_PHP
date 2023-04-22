<?php

namespace Src\Core\Exceptions;

use Throwable;

class UserActivationException extends \RuntimeException
{
  public function __construct(
    public readonly array $errors,
    string $message = 'cannot activate this account',
    int $code = 422,
    ?Throwable $previous = null
  ) {
    parent::__construct($message, $code, $previous);
  }
}
