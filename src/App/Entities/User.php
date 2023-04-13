<?php

namespace Src\App\Entities;

use Src\Core\Interfaces\UserInterface;
use Src\Core\Abstracted\Entity;
use Src\Core\Attributes\Validation\{
  Length,
  Required,
  Password,
  Email,
  Number,
  Unique
};
use Src\Core\Behavior\{
  EntityDateBehavior,
  EntityNameBehavior
};

class User extends Entity implements UserInterface
{

  use EntityDateBehavior;
  use EntityNameBehavior;

  #[Unique]
  #[Length(10, 50)]
  #[Email, Required]
  public ?string $email = null;

  #[Password, Required]
  public ?string $password = null;

  #[Number]
  public ?int $category_id = null;

  public ?string $role = null;

  public function setEmail(string $email): self
  {
    $this->email = $email;
    return $this;
  }

  public function setPassword(?string $password): self
  {
    $this->password = $password;
    return $this;
  }

  public function getUserSafe(): ?self
  {
    $this->setPassword(null);
    return $this;
  }

  public function getId(): int
  {
    return $this->id;
  }

  public function getPassword(): string
  {
    return $this->password;
  }

  public function setRole(string $role)
  {
    $this->role = $role;
    return $this;
  }

  public function databaseFormat(): array
  {
    $data = [];
    foreach ($this as $prop) {
      if ($this->$prop && $prop !== 'id')
        $data[$prop] = $this->$prop;
    }
    return $data;
  }
}
