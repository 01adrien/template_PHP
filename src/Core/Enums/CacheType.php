<?php

namespace Src\Core\Enums;

enum CacheType: string
{
    case Memory = 'memory';
    case File = 'file';
    case Memcache = 'memcache';
    case Redis = 'redis';
}
