<?php

namespace Src\Core\Exceptions;

use Throwable;

class SessionException extends \RuntimeException
{
  public function __construct(
    public readonly array $errors,
    string $message = 'Failed to persit user into the session',
    int $code = 422,
    ?Throwable $previous = null
  ) {
    parent::__construct($message, $code, $previous);
  }
}
