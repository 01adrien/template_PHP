<?php

namespace Src\Core\Interfaces;

use Src\Core\DataObjects\RegisterUserData;

interface UserServiceInterface
{
  public function getById(int $id): UserInterface | bool;
  public function getByEmail(string $email): UserInterface | bool | array;
  public function passwordsMatch(RegisterUserData $data): bool;
  public function hashPassword(string $password): string;
  public function createUser(RegisterUserData $data): UserInterface | bool;
}
