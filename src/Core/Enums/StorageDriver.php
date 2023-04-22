<?php

namespace Src\Core\Enums;

enum StorageDriver: string
{
    case Local = 'local';
    case Ftp = 'ftp';
    case S3 = 'S3';
}
