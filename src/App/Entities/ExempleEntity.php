<?php

namespace Src\App\Entities;

use \Src\Core\Behavior\EntityDateBehavior;
use Src\Core\Abstracted\Entity;
use Src\Core\Attributes\Validation\Length;
use Src\Core\Attributes\Validation\Required;

/** @psalm-suppress PropertyNotSetInConstructor */
class ExempleEntity extends Entity
{

    use EntityDateBehavior;

    /** @var string */
    #[Length(2, 50), Required]
    public string $title;
  
    /** @var string */
    #[Length(2, 1000), Required]
    public string $content;

    public function __construct()
    {
    }

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