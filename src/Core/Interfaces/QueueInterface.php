<?php

namespace Src\Core\Interfaces;

use Src\App\Entities\Job;

interface QueueInterface
{
  public function push(Job $job, string $queue): void;
  public function shift(string $queue): Job | bool;
  public function isEmpty(string $queue): bool;
  public function getConfig(): array;
}
