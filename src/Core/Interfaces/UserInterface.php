<?php

namespace Src\Core\Interfaces;

interface UserInterface
{
  public function getId(): int;
  public function getPassword(): string;
  public function getUserSafe(): ?self;
  public function getEmail(): string;
}
