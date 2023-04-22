<?php

namespace Src\Core\Storage\Factory;

use Exception;
use Src\Core\Config\Config;
use Src\Core\Enums\StorageDriver;
use Src\Core\Interfaces\StorageInterface;
use Src\Core\Storage\StorageSystem\FtpStorage;
use Src\Core\Storage\StorageSystem\LocalStorage;
use Src\Core\Storage\StorageSystem\S3Storage;

class StorageFactory
{
    public function __invoke(Factory $factory, Config $config): StorageInterface
    {
        switch ($config->get('strorage.default')) {
            case StorageDriver::Local:
                if (!$factory->hasDriver(StorageDriver::Local->value)) {
                    $factory->addDriver(
                        'local',
                        fn (array $config) => new LocalStorage($config)
                    );
                }
                return $factory->make($config->get('storage.local'));

            case StorageDriver::Ftp:
                if (!$factory->hasDriver(StorageDriver::Ftp->value)) {
                    $factory->addDriver(
                        'ftp',
                        fn (array $config) => new FtpStorage($config)
                    );
                }
                return $factory->make($config->get('storage.ftp'));

            case StorageDriver::S3:
                if (!$factory->hasDriver(StorageDriver::S3->value)) {
                    $factory->addDriver(
                        'S3',
                        fn (array $config) => new S3Storage($config)
                    );
                }
                return $factory->make($config->get('storage.S3'));

            default:
                throw new Exception('you must choose default storage system in config file');
        }
    }
}
