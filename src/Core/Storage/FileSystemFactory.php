<?php

namespace Src\Core\Storage;

use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Psr\Container\ContainerInterface;
use Src\Core\Config\Config;
use Src\Core\Enums\StorageDriver;

class FileSystemFactory
{
  public function __invoke(ContainerInterface $container)
  {
    $adapter = match ($container->get(Config::class)->get('storage.driver')) {
      StorageDriver::local => new LocalFilesystemAdapter(STORAGE_DIR)
    };
    return new Filesystem($adapter);
  }
}
