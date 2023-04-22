<?php

namespace Src\Core\Cache\CacheSystem;

use Predis\Client;
use Src\Core\Interfaces\CacheInterface;

class RedisCache implements CacheInterface
{
    private Client $redisClient;

    public function __construct(private array $config)
    {
        $this->redisClient = new Client("{$config['host']}:{$config['port']}");
        $this->redisClient->auth($config['pass']);
        $this->redisClient->select($config['database']);
    }
    public function has(string $key): bool
    {
        return !empty($this->redisClient->get($key));
    }
    public function get(string $key, mixed $default = null): mixed
    {
        if ($value = $this->redisClient->get($key)) {
            return $value;
        }
        return $default;
    }
    public function put(string $key, mixed $value, int $seconds = null): static
    {
        if (!is_int($seconds)) {
            $seconds = (int) $this->config['seconds'];
        }
        $this->redisClient->set($key, $value);
        $this->redisClient->expire($key, $seconds);
        return $this;
    }
    public function forget(string $key): static
    {
        $this->redisClient->del($key);
        return $this;
    }
    public function flush(): static
    {
        $this->redisClient->flushall();
        return $this;
    }
}
