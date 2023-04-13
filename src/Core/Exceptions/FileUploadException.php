<?php

namespace Src\Core\Exceptions;

use Throwable;

class FileUploadException extends \RuntimeException
{
  public function __construct(
    public readonly array $errors,
    string $message = 'cannot upload this file',
    int $code = 422,
    ?Throwable $previous = null
  ) {
    parent::__construct($message, $code, $previous);
  }
}
