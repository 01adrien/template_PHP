<?php

namespace Src\Core\Behavior;

use DateTime;
use Exception;
use Src\Core\Attributes\Validation\Date;

trait EntityDateBehavior
{
  /** @var string */
  #[Date]
  public string $created_at;
  
  /** @var string */
  #[Date]
  public string $updated_at;

  public function getDateObject(string $date): DateTime
  {
    return $date = DateTime::createFromFormat('Y-m-d H:i:s',$date);
  }

  public function setCreatedAt(DateTime $date): self
  {
    $this->created_at = $date->format('Y-m-d H:i:s');
    return $this;
  }

  public function setUpdatedAt(DateTime $date): self
  {
    $this->updated_at = $date->format('Y-m-d H:i:s');
    return $this;
  }
}