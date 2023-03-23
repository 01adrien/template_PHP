<?php

namespace Src\App\Models;

use Src\App\Entities\ExempleEntity;
use Src\Core\Interfaces\ValidationInterface;
use Src\Core\Abstracted\{
  Entity,
  Model
};
use Src\Core\Validator\ErrorsObject;
use Src\Core\Validator\Validator;

class ExempleModel extends Model implements ValidationInterface
{
  protected string $table = 'exemple';
  protected $entity = ExempleEntity::class;


  public function __construct(
    private Validator $validator,
    private \PDO $pdo
  ) {
    parent::__construct($pdo);
  }

  /**
   * validate provided data in relation with ExempleEntity properties attributes
   *
   * @param  ExempleEntity $entity
   */
  public function validate(Entity $entity): ErrorsObject
  {
    $reflection = new \ReflectionClass($this->entity);
    $properties = $reflection->getProperties();
    foreach ($properties as $property) {
      $this
        ->validator
        ->isRequired($entity, $property)
        ->validateLength($entity, $property);
    }
    return $this->validator->getErrors();
  }
}
