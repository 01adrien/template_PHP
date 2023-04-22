<?php

namespace Src\Core\Enums;

enum DatabaseType: string
{
    case Mysql = 'mysql';
    case Sqlite = 'sqlite';
}
