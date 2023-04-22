<?php

namespace Src\Core\Cache\Factory;

use Exception;
use Src\Core\Cache\CacheSystem\FileCache;
use Src\Core\Cache\CacheSystem\MemoryCache;
use Src\Core\Cache\CacheSystem\MemcacheCache;
use Src\Core\Cache\CacheSystem\RedisCache;
use Src\Core\Config\Config;
use Src\Core\Enums\CacheType;
use Src\Core\Interfaces\CacheInterface;

class CacheFactory
{
    public function __invoke(Config $config, Factory $factory): CacheInterface
    {

        switch ($config->get('cache.default')) {
            case CacheType::File:
                if (!$factory->hasDriver(CacheType::File->value)) {
                    $factory->addDriver(
                        'file',
                        fn (array $config) => new FileCache($config)
                    );
                }
                return $factory->make($config->get('cache.file'));

            case CacheType::Memory:
                if (!$factory->hasDriver(CacheType::Memory->value)) {
                    $factory->addDriver(
                        'memory',
                        fn (array $config) => new MemoryCache($config)
                    );
                }
                return $factory->make($config->get('cache.memory'));

            case CacheType::Memcache:
                if (!$factory->hasDriver(CacheType::Memcache->value)) {
                    $factory->addDriver(
                        'memcache',
                        fn (array $config) => new MemcacheCache($config)
                    );
                }
                return $factory->make($config->get('cache.memcache'));

            case CacheType::Redis:
                if (!$factory->hasDriver(CacheType::Redis->value)) {
                    $factory->addDriver(
                        'redis',
                        fn (array $config) => new RedisCache($config)
                    );
                }
                return $factory->make($config->get('cache.redis'));

            default:
                throw new Exception('you must choose default cache system on config file');
        }
    }
}
