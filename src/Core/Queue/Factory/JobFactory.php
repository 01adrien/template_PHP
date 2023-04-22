<?php

namespace Src\Core\Queue\Factory;

use Closure;
use Laravel\SerializableClosure\SerializableClosure;
use Src\App\Entities\Job;

class JobFactory
{
    public function make(Closure $closure, array $params): Job
    {
        $wrapper = new SerializableClosure($closure);

        $job = (new Job)
        ->setUniqid(uniqid())
        ->setClosure(serialize($wrapper))
        ->setParams(serialize($params))
        ->setAttempts(0)
        ->setIsComplete(0);

        return $job;
    }
}
