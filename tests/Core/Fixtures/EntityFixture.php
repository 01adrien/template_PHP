<?php

namespace Tests\Core\Fixtures;

use Src\Core\Abstracted\Entity;
use Src\Core\Attributes\Validation\{
  Email,
  Number,
  Password,
  Required
};
use Src\Core\Behavior\{
  EntityDateBehavior,
  EntityNameBehavior
};

class EntityFixture extends Entity
{
  use EntityNameBehavior;
  use EntityDateBehavior;

  #[Email]
  public ?string $email = null;

  #[Required]
  public ?string $requiredField = null;

  #[Password]
  public ?string $password = null;

  #[Number]
  public ?string $money = null;
}
