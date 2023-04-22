<?php

namespace Src\Core\Storage\StorageSystem;

use League\Flysystem\Filesystem;
use League\Flysystem\Ftp\FtpAdapter;
use League\Flysystem\Ftp\FtpConnectionOptions;
use Src\Core\Interfaces\StorageInterface;

class FtpStorage implements StorageInterface
{
    public function __construct(private array $config)
    {
    }

    public function getStorage(): Filesystem
    {
        $adapter = new FtpAdapter(
            FtpConnectionOptions::fromArray([
            'host' => $this->config['host'],
            'root' => $this->config['root'],
            'username' => $this->config['user'],
            'password' => $this->config['pass'],
            ])
        );
        return new Filesystem($adapter);
    }
}
