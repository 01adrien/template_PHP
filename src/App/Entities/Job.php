<?php

namespace Src\App\Entities;

use Laravel\SerializableClosure\SerializableClosure;
use Src\Core\Abstracted\Entity;

/** @psalm-suppress PropertyNotSetInConstructor */
class Job extends Entity
{
    /** @var string */
    public string $closure;
    
    /** @var string */
    public string $params;

    /** @var int */
    public int $attempts;

    /** @var int */
    public int $is_complete;

    /** @var string */
    public string $queue;
    
    /** @var string */
    public string $uniqid;

    public function __construct()
    {
    }
    public function setClosure(string $closure): static
    {
        $this->closure = $closure;
        return $this;
    }

    public function setParams(string $params): static
    {
        $this->params = $params;
        return $this;
    }

    public function setAttempts(int $n): static
    {
        $this->attempts = $n;
        return $this;
    }

    public function setIsComplete(int $n): static
    {
        $this->is_complete = $n;
        return $this;
    }

    public function setQueue(string $name): static
    {
        $this->queue = $name;
        return $this;
    }

    public function setUniqid(string $id): static
    {
        $this->uniqid = $id;
        return $this;
    }

    public function run(): mixed
    {   
        /** @var SerializableClosure $closure */
        $closure = unserialize($this->closure);
        /** @var array $params */
        $params = unserialize($this->params);
        return call_user_func_array($closure->getClosure(), [$params]);
    }
}