<?php

namespace Src\App\Models;

use Src\Core\Abstracted\Entity;
use Src\Core\Abstracted\Model;
use Src\Core\Interfaces\ValidationInterface;
use Src\Core\Validator\Validator;
use Src\Core\Validator\ErrorsObject;
use Src\App\Entities\User;
use Src\Core\Interfaces\ConnectionInterface;

class UserModel extends Model implements ValidationInterface
{
    protected string $table = 'user';
    protected string $entity = User::class;

    public function __construct(
        private Validator $validator,
        private ConnectionInterface $connectionInterface
    ) {
        parent::__construct($connectionInterface);
    }

  /**
   * validate user input when update or insert on user table
   *
   * @param  Entity $entity
   * @return ErrorsObject
   */
    public function validate(Entity $entity): ErrorsObject
    {
        $reflection = new \ReflectionClass($this->entity);
        $properties = $reflection->getProperties();

        foreach ($properties as $property) {
            $this
            ->validator
            ->isRequired($entity, $property)
            ->validateLength($entity, $property)
            ->validateName($entity, $property)
            ->validatePassword($entity, $property)
            ->validateEmail($entity, $property)
            ->isUnique($entity, $property, $this, 'email');
        }
        return $this->validator->getErrorObject();
    }
}