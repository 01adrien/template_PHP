<?php

namespace Tests\Core\Validator;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Src\Core\Validator\{Validator, ErrorsObject};
use Tests\Core\Fixtures\EntityFixture;

class TestValidator extends TestCase
{
  private EntityFixture $entity;
  private Validator $validator;
  private ReflectionClass $reflectionClass;

  public function setUp(): void
  {
    $this->validator = new Validator(new ErrorsObject());
    $this->entity = new EntityFixture();
    $this->reflectionClass = new ReflectionClass($this->entity);
  }

  public function testLengthRule()
  {
    $prop = $this->reflectionClass->getProperty('name');
    $this->validator->validateLength($this->entity, $prop);
    $this->entity->setName('nnf');
    $expectedError = [
      'name' => 'name length should be between 5 & 30'
    ];
    $this->assertEquals(
      $expectedError,
      $this->validator->getErrorObject()->getErrors()
    );
    $this->validator->getErrorObject()->deleteErrors();
    $this->entity->setName('bobby');
    $this->validator->validateLength($this->entity, $prop);
    $this->assertEquals(
      [],
      $this->validator->getErrorObject()->getErrors()
    );
  }

  public function testRequiredRule()
  {
    $prop = $this->reflectionClass->getProperty('requiredField');
    $this->validator->isRequired($this->entity, $prop);
    $expectedError = [
      'requiredField' => 'requiredField field is required'
    ];
    $this->assertEquals(
      $expectedError,
      $this->validator->getErrorObject()->getErrors()
    );
    $this->validator->getErrorObject()->deleteErrors();
    $this->entity->requiredField = 'adsead';
    $this->validator->isRequired($this->entity, $prop);

    $this->assertEquals(
      [],
      $this->validator->getErrorObject()->getErrors()
    );
  }

  public function testEmailRule()
  {
    $prop = $this->reflectionClass->getProperty('email');
    $this->entity->email = 'gfgftgfjgj.fr';
    $this->validator->validateEmail($this->entity, $prop);
    $expectedError = [
      'email' => 'email not valid'
    ];
    $this->assertEquals(
      $expectedError,
      $this->validator->getErrorObject()->getErrors()
    );
    $this->validator->getErrorObject()->deleteErrors();
    $this->entity->email = 'johndoe@outlook.fr';
    $this->validator->validateEmail($this->entity, $prop);
    $this->assertEquals(
      [],
      $this->validator->getErrorObject()->getErrors()
    );
  }

  public function testNameRule()
  {
    $prop = $this->reflectionClass->getProperty('name');
    $this->entity->setName('bobby189/*55');
    $this->validator->validateName($this->entity, $prop);
    $expectedError = [
      'name' => 'name not valid, only letters are allowed'
    ];
    $this->assertEquals(
      $expectedError,
      $this->validator->getErrorObject()->getErrors()
    );
    $this->validator->getErrorObject()->deleteErrors();
    $this->entity->setName('Bobby');
    $this->validator->validateName($this->entity, $prop);
    $this->assertEquals(
      [],
      $this->validator->getErrorObject()->getErrors()
    );
  }

  public function testPasswordRule()
  {
    $prop = $this->reflectionClass->getProperty('password');
    $this->entity->password = 'notvalidpassword';
    $this->validator->validatePassword($this->entity, $prop);
    $expectedError = [
      'password' => 'password not valid, 6 to 20 characters and at least 1 numeric, 1 upperCase and 1 lower case'
    ];
    $this->assertEquals(
      $expectedError,
      $this->validator->getErrorObject()->getErrors()
    );
    $this->validator->getErrorObject()->deleteErrors();
    $this->entity->password = 'SuperSecure145*';
    $this->validator->validatePassword($this->entity, $prop);
    $this->assertEquals(
      [],
      $this->validator->getErrorObject()->getErrors()
    );
  }

  public function testNumberRule()
  {
    $prop = $this->reflectionClass->getProperty('money');
    $this->entity->money = 'hgld*/';
    $this->validator->validateNumber($this->entity, $prop);
    $expectedError = [
      'money' => 'money not valid, must be a number'
    ];
    $this->assertEquals(
      $expectedError,
      $this->validator->getErrorObject()->getErrors()
    );
    foreach ([45, '458', 87.2, '987.5'] as $item) {
      $this->validator->getErrorObject()->deleteErrors();
      $this->entity->money = $item;
      $this->validator->validateNumber($this->entity, $prop);
      $this->assertEquals(
        [],
        $this->validator->getErrorObject()->getErrors()
      );
    }
  }
}
