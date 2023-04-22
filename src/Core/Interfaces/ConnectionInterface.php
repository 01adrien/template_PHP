<?php

namespace Src\Core\Interfaces;

use PDO;

interface ConnectionInterface
{
  public function pdo(): PDO;
}
