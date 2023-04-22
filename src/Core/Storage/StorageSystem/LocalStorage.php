<?php

namespace Src\Core\Storage\StorageSystem;

use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Src\Core\Interfaces\StorageInterface;

class LocalStorage implements StorageInterface
{
    public function __construct(private array $config)
    {
    }
    public function getStorage(): Filesystem
    {
        $adapter = new LocalFilesystemAdapter($this->config['path']);
        return new Filesystem($adapter);
    }
}
