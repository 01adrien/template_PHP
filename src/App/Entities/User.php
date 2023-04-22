<?php

namespace Src\App\Entities;

use Src\Core\Interfaces\UserInterface;
use Src\Core\Abstracted\Entity;
use Src\Core\Attributes\Validation\Length;
use Src\Core\Attributes\Validation\Required;
use Src\Core\Attributes\Validation\Password;
use Src\Core\Attributes\Validation\Email;
use Src\Core\Attributes\Validation\Unique;
use Src\Core\Behavior\EntityDateBehavior;
use Src\Core\Behavior\EntityNameBehavior;

/** @psalm-suppress PropertyNotSetInConstructor */
class User extends Entity implements UserInterface
{

    use EntityDateBehavior;
    use EntityNameBehavior;

    /** @var string */

    #[Unique]
    #[Length(10, 50)]
    #[Email, Required]
    public string $email;

    /** @var string */

    #[Password, Required]
    public string $password;

    /** @var string */
    public string $role;

    /** @var int */
    public int $active;

    public function __construct()
    {
    }
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getUserSafe(): ?self
    {
        $this->setPassword('null');
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

    public function setRole(string $role): self
    {
        $this->role = $role;
        return $this;
    }

    public function setActive(int $n): self
    {
        $this->active = $n;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}