<?php

namespace Src\Core\Storage\StorageSystem;

use Aws\S3\S3Client;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use League\Flysystem\Filesystem;
use Src\Core\Interfaces\StorageInterface;

class S3Storage implements StorageInterface
{
    public function __construct(private array $config)
    {
    }

    public function getStorage(): Filesystem
    {
        $client = new S3Client([]);
        $adapter = new AwsS3V3Adapter(
            $client,
            'bucket-name'
        );
        return new Filesystem($adapter);
    }
}
