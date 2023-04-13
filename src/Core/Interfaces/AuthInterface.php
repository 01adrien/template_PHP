<?php

namespace Src\Core\Interfaces;

use Src\Core\DataObjects\{Credentials, RegisterUserData};

interface AuthInterface
{
  public function currentUser(): ?UserInterface;
  public function attemptLogin(Credentials $credentials): ?UserInterface;
  public function login(UserInterface $user): void;
  public function logOut(): void;
  public function register(RegisterUserData $data): UserInterface;
}
