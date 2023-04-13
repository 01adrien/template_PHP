<?php

namespace Src\App\Entities;

use DateTime;
use \Src\Core\Behavior\EntityDateBehavior;
use Src\Core\Abstracted\Entity;
use Src\Core\Attributes\Validation\{
  Date,
  Length,
  Required
};

class ExempleEntity extends Entity
{

  use EntityDateBehavior;

  #[Length(2, 50), Required]
  public ?string $title = null;

  #[Length(2, 1000), Required]
  public ?string $content = null;

  #[Date]
  public string|DateTime|null $created_at = null;

  public function setTitle(string $title): self
  {
    $this->title = trim($title);
    return $this;
  }

  public function setContent(string $content): self
  {
    $this->content = trim($content);
    return $this;
  }
}
