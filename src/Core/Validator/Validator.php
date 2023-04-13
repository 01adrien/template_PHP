<?php

namespace Src\Core\Validator;

use Psr\Http\Message\UploadedFileInterface;
use ReflectionProperty;
use Src\Core\Abstracted\{
  Entity,
  Model
};
use Src\Core\Attributes\Validation\{
  Email,
  Length,
  Name,
  Number,
  Password,
  Required,
  Unique
};
use Src\Core\Exceptions\DataValidationException;

class Validator
{

  public function __construct(private ErrorsObject $errors)
  {
  }
  public function validateLength(Entity $entity, ReflectionProperty $property): self
  {
    $prop = $property->getName();
    if (
      !$entity->$prop &&
      !$property->getAttributes(Required::class)
    ) {
      return $this;
    }
    $this->readAttributes(
      $property,
      $entity,
      Length::class,
    );
    return $this;
  }

  public function isRequired(Entity $entity, ReflectionProperty $property): self
  {
    $this->readAttributes(
      $property,
      $entity,
      Required::class,
    );
    return $this;
  }

  public function validateEmail(Entity $entity, ReflectionProperty $property): self
  {
    $this->readAttributes(
      $property,
      $entity,
      Email::class,
    );
    return $this;
  }

  public function validateName(Entity $entity, ReflectionProperty $property): self
  {
    $this->readAttributes(
      $property,
      $entity,
      Name::class,
    );
    return $this;
  }

  public function validatePassword(Entity $entity, ReflectionProperty $property): self
  {
    $this->readAttributes(
      $property,
      $entity,
      Password::class,
    );
    return $this;
  }

  public function validateNumber(Entity $entity, ReflectionProperty $property): self
  {
    $this->readAttributes(
      $property,
      $entity,
      Number::class,
    );
    return $this;
  }

  public function isUnique(Entity $entity, ReflectionProperty $property, Model $model, string $field): self
  {
    $this->readAttributes(
      $property,
      $entity,
      Unique::class,
      $model,
      $field,
    );
    return $this;
  }

  private function readAttributes(
    ReflectionProperty $property,
    Entity $entity,
    string $attributeName,
    Model $model = null,
    string $field = null
  ): void {

    $attributes = $property->getAttributes(
      $attributeName,
      \ReflectionAttribute::IS_INSTANCEOF
    );
    if ($attributes) {
      $prop = $property->getName();
      foreach ($attributes as $attr) {
        $isValid = $attr
          ->newInstance()
          ->isValid($entity, $prop, $model, $field);
        if ($isValid !== true) {
          $this->errors->push($isValid['type'], $isValid['message']);
        }
      }
    }
  }


  public function validateFile(
    array $data,
    array $allowedMimeTypes,
    array $allowedExtension,
    string $inputName
  ): UploadedFileInterface {

    /** @var UploadedFileInterface $uploadedFile  */
    $uploadedFile = $data[$inputName];

    if (!$uploadedFile) {
      throw new DataValidationException([
        $inputName => "please select a $inputName file"
      ]);
    }

    $maxSize = 5 * 1024 * 1024;
    $fileName = $uploadedFile->getClientFilename();
    $tmpFilePath = $uploadedFile->getStream()->getMetadata('uri');
    $extension = (new \finfo(FILEINFO_EXTENSION))->file($tmpFilePath) ?: '';
    $mimeType = (new \finfo(FILEINFO_MIME))->file($tmpFilePath) ?: '';

    $mimeType = substr($mimeType, 0, strpos($mimeType, ';'));


    if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
      throw new DataValidationException([
        $inputName => 'failed to upload the file'
      ]);
    }
    if ($uploadedFile->getSize() > $maxSize) {
      throw new DataValidationException([
        $inputName => 'maximun allowed size is 5MB'
      ]);
    }
    if (!preg_match('/^[a-zA-z0-9\s._-]+$/', $fileName)) {
      throw new DataValidationException([
        $inputName => 'file name is invalid'
      ]);
    }
    if (!in_array($uploadedFile->getClientMediaType(), $allowedMimeTypes)) {
      throw new DataValidationException([
        $inputName => 'format not are supported'
      ]);
    }
    if (!in_array($extension, $allowedExtension)) {
      throw new DataValidationException([
        $inputName => 'extension not supported'
      ]);
    }
    if (!in_array($mimeType, $allowedMimeTypes)) {
      throw new DataValidationException([
        $inputName => 'invalid type file'
      ]);
    }

    return $uploadedFile;
  }


  public function getErrorObject(): ErrorsObject
  {
    return $this->errors;
  }
}
