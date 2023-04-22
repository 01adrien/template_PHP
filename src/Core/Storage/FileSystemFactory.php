<?php

namespace Src\Core\Storage;

use League\Flysystem\Filesystem;
use League\Flysystem\Ftp\FtpAdapter;
use League\Flysystem\Ftp\FtpConnectionOptions;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Src\Core\Config\Config;
use Src\Core\Enums\StorageDriver;

class FileSystemFactory
{
    public function __invoke(Config $config)
    {
        switch ($config->get('storage.default')) {
            case StorageDriver::Local:
                $adapter = new LocalFilesystemAdapter($config->get('storage.local.path'));
                return new Filesystem($adapter);

            case StorageDriver::Ftp:
                $adapter = new FtpAdapter(
                    FtpConnectionOptions::fromArray([
                    'host' => $config->get('storage.ftp.host'),
                    'root' => $config->get('storageftp.root'),
                    'username' => $config->get('storage.ftp.user'),
                    'password' => $config->get('storage.ftp.pass'),
                    ])
                );
                new Filesystem($adapter);
        };
    }
}
