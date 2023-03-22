<?php

namespace Src\App\Entities;

use DateTime;
use Src\Core\Abstracted\Entity;

class ExempleEntity extends Entity
{
  public ?int $id = null;
  public ?string $title = null;
  public ?string $content = null;
  public string|DateTime|null $created_at = null;

  public function __construct()
  {
    if ($this->created_at) {
      $this->created_at = DateTime::createFromFormat(
        'Y-m-d H:i:s',
        $this->created_at
      );
    }
  }

  public function setTitle(string $title): self
  {
    $this->title = $title;
    return $this;
  }

  public function setContent(string $content): self
  {
    $this->content = $content;
    return $this;
  }

  public function setCreatedAt(DateTime $date): self
  {
    $this->created_at = $date->format('Y-m-d H:i:s');
    return $this;
  }
}
