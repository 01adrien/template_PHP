<?php

namespace Src\Core\Interfaces;

use Src\Core\Abstracted\Entity;
use Src\Core\Validator\ErrorsObject;

interface ValidationInterface
{
  public function validate(Entity $entity): ErrorsObject;
}
