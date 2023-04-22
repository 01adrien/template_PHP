<?php

namespace Src\Core\Enums;

enum QueueSystem: string
{
    case Database = 'database';
    case Redis = 'redis';
}
