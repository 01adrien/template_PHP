<?php


namespace Src\Core\Database\Connection;



use InvalidArgumentException;
use PDO;
use Src\Core\Abstracted\AbstractSystem;
use Src\Core\Interfaces\ConnectionInterface;

class SqliteConnection implements ConnectionInterface
{
  private Pdo $pdo;

  public function __construct(array $config)
  {
    ['path' => $path] = $config;

    if (empty($path)) {
      throw new InvalidArgumentException('Connection incorrectly configured');
    }

    $this->pdo = new PDO("sqlite:{$path}");
  }

  public function pdo(): Pdo
  {
    return $this->pdo;
  }
}
