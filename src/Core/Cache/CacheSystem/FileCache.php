<?php

namespace Src\Core\Cache\CacheSystem;

use Src\Core\Interfaces\CacheInterface;

class FileCache implements CacheInterface
{
    private array $config = [];
    private array $cached = [];

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function has(string $key): bool
    {
        $data = $this->cached[$key] = $this->read($key);

        return isset($data['expires']) and $data['expires'] > time();
    }

    private function path(string $key): string
    {
        $base = $this->base();
        $separator = DIRECTORY_SEPARATOR;
        $key = sha1($key);

        return "{$base}{$separator}{$key}.json";
    }

    private function base(): string
    {
        return STORAGE_DIR . '/cache';
    }

    private function read(string $key): mixed
    {
        $path = $this->path($key);

        if (!is_file($path)) {
            return [];
        }

        return json_decode(file_get_contents($path), true);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        if ($this->has($key)) {
            return $this->cached[$key]['value'];
        }

        return $default;
    }

    public function put(string $key, mixed $value, int $seconds = null): static
    {
        if (!is_int($seconds)) {
            $seconds = (int) $this->config['seconds'];
        }

        $data = $this->cached[$key] = [
        'value' => $value,
        'expires' => time() + $seconds,
        ];

        return $this->write($key, $data);
    }

    private function write(string $key, mixed $value): static
    {
        file_put_contents($this->path($key), json_encode($value));
        return $this;
    }

    public function forget(string $key): static
    {
        unset($this->cached[$key]);

        $path = $this->path($key);

        if (is_file($path)) {
            unlink($path);
        }

        return $this;
    }

    public function flush(): static
    {
        $this->cached = [];

        $base = $this->base();
        $separator = DIRECTORY_SEPARATOR;

        $files = glob("{$base}{$separator}*.json");

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        return $this;
    }
}