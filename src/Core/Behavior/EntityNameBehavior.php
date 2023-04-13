<?php

namespace Src\Core\Behavior;

use Src\Core\Attributes\Validation\{Length, Name, Required};

trait EntityNameBehavior
{
  #[Length(5, 30), Name, Required]
  public ?string $name = null;

  public function setName(string $name): self
  {
    $this->name = $name;
    return $this;
  }
}
