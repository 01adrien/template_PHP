<?php

namespace Src\Core\Database\Connection;

use InvalidArgumentException;
use PDO;
use Src\Core\Interfaces\ConnectionInterface;

class MysqlConnection implements ConnectionInterface
{
  private PDO $pdo;

  public function __construct(array $config)
  {
    [
      'host' => $host,
      'port' => $port,
      'name' => $database,
      'user' => $username,
      'pass' => $password,
    ] = $config;

    if (empty($host) || empty($database) || empty($username)) {
      throw new InvalidArgumentException('Connection incorrectly configured');
    }

    $this->pdo = new Pdo("mysql:host={$host};port={$port};dbname={$database}", $username, $password);
  }

  public function pdo(): Pdo
  {
    return $this->pdo;
  }
}
