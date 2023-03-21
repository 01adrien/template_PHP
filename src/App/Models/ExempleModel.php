<?php

namespace Src\App\Models;

use Src\App\Entities\ExempleEntity;
use Src\Core\Abstracted\Model;

class ExempleModel extends Model
{
  protected string $table = 'exemple';
  protected $entity = ExempleEntity::class;
}
