<?php

namespace Src\App\Entities;

use DateTime;
use Exception;
use Src\Core\Abstracted\Entity;
use Src\Core\Attributes\Validation\Length;

class ExempleEntity extends Entity
{
  public ?int $id = null;

  #[Length(2, 50)]
  public ?string $title = null;

  #[Length(2, 1000)]
  public ?string $content = null;

  private string|DateTime|null $created_at = null;

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

  public function setCreatedAt(DateTime $date): self
  {
    $this->created_at = $date->format('Y-m-d H:i:s');
    return $this;
  }

  public function getDateObject(): DateTime
  {
    if (is_string($this->created_at)) {
      return $this->created_at = DateTime::createFromFormat(
        'Y-m-d H:i:s',
        $this->created_at
      );
    } else throw new Exception('the date must be a string');
  }
}
