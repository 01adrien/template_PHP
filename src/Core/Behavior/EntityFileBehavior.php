<?php

namespace Src\Core\Behavior;

trait EntityFileBehavior
{
  public ?string $filename = null;
  public ?string $storage_filename = null;
  public ?string $mime_type = null;
  public ?string $file_size = null;

  public function setStorageFileName(string $name): self
  {
    $this->storage_filename = $name;
    return $this;
  }

  public function setMimeType(string $mimeType): self
  {
    $this->mime_type = $mimeType;
    return $this;
  }

  public function setFileSize(string $size): self
  {
    $this->file_size = $size;
    return $this;
  }

  public function setFileName(string $name): self
  {
    $this->filename = $name;
    return $this;
  }
}
