<?php

namespace Src\Core\Interfaces;

use League\Flysystem\Filesystem;

interface StorageInterface
{
  public function getStorage(): Filesystem;
}
