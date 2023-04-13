<?php

namespace Src\Core\DataObjects;

class RegisterUserData
{

  public function __construct(
    public string $name,
    public string $email,
    public string $password,
    public string $confirm_password
  ) {
  }
}
