<?php

namespace Src\App\Models;

use Src\App\Entities\ExempleEntity;
use Src\Core\Interfaces\ValidationInterface;
use Src\Core\Abstracted\{
  Entity,
  Model
};
use Src\Core\Attributes\Validation\Length;
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
   * @param  mixed $entity
   */
  public function validate(Entity $entity): ErrorsObject
  {
    $reflection = new \ReflectionClass($this->entity);
    $properties = $reflection->getProperties();
    foreach ($properties as $property) {
      /** check the lehgth attribute */
      $lengthAttrs = $property->getAttributes(
        Length::class,
        \ReflectionAttribute::IS_INSTANCEOF
      );
      foreach ($lengthAttrs as $attr) {
        $this->validator->validateLength(
          $entity,
          $attr->newInstance(),
          $property->getName()
        );
      }
    }
    return $this->validator->getErrors();
  }
}
