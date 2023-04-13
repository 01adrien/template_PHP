<?php

namespace Src\Core\DataObjects;

class Credentials
{
  public function __construct(
    public string $email,
    public string $password
  ) {
  }
}
