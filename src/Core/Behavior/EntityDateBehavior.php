<?php

namespace Src\Core\Behavior;

use DateTime;
use Exception;
use Src\Core\Attributes\Validation\Date;

trait EntityDateBehavior
{
  #[Date]
  public string|DateTime|null $created_at = null;

  #[Date]
  public string|DateTime|null $updated_at = null;

  public function getDateObject(string $date): DateTime
  {
    if (is_string($date)) {
      return $date = DateTime::createFromFormat(
        'Y-m-d H:i:s',
        $date
      );
    } else throw new Exception('the date must be a string');
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
