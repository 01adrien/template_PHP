<?php

namespace Src\Core\Exceptions;

use Throwable;

class EmptyQueueException extends \RuntimeException
{
  public function __construct(
    public readonly array $errors,
    string $message = 'the queue is empty',
    int $code = 422,
    ?Throwable $previous = null
  ) {
    parent::__construct($message, $code, $previous);
  }
}
