<?php

namespace Src\App\Models;

use Src\App\Entities\ExempleEntity;
use Src\Core\Interfaces\ValidationInterface;
use Src\Core\Abstracted\Entity;
use Src\Core\Abstracted\Model;
use Src\Core\Interfaces\ConnectionInterface;
use Src\Core\Validator\ErrorsObject;
use Src\Core\Validator\Validator;

class ExempleModel extends Model implements ValidationInterface
{
    protected string $table = 'exemple';
    protected string $entity = ExempleEntity::class;


    public function __construct(
        private Validator $validator,
        private ConnectionInterface $connectionInterface
    ) {
        parent::__construct($connectionInterface);
    }

  /**
   * validate provided data in relation with ExempleEntity properties attributes
   *
   * @param  Entity $entity
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
        return $this->validator->getErrorObject();
    }
}