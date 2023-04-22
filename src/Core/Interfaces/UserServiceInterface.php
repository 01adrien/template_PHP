<?php

namespace Src\Core\Interfaces;

use Src\Core\DataObjects\RegisterUserData;

interface UserServiceInterface
{
  public function getById(int $id): ?UserInterface;
  public function getByEmail(string $email): ?UserInterface;
  public function passwordsMatch(RegisterUserData $data): bool;
  public function hashPassword(string $password): string;
  public function createUser(RegisterUserData $data): UserInterface | bool;
  public function deleteUser(UserInterface $user): void;
  public function resetPassword(UserInterface $user): void;
}
