<?php

namespace Src\Core\Behavior;

trait EntityFileBehavior
{
    public string $filename;
    public string $storage_filename;
    public string $mime_type;
    public string $file_size;

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
