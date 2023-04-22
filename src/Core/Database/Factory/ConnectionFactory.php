<?php

namespace Src\Core\Database\Factory;

use Exception;
use Src\Core\Config\Config;
use Src\Core\Database\Connection\MysqlConnection;
use Src\Core\Database\Connection\SqliteConnection;
use Src\Core\Enums\DatabaseType;
use Src\Core\Interfaces\ConnectionInterface;

class ConnectionFactory
{
  public function __invoke(Config $config, Factory $factory): ConnectionInterface
  {

    switch ($config->get('database.default')) {

      case DatabaseType::Mysql:
        if (!$factory->hasDriver(DatabaseType::Mysql->value)) {
          $factory->addDriver(
            'mysql',
            fn (array $config) => new MysqlConnection($config)
          );
        }
        return $factory->make($config->get('database.mysql'));

      case DatabaseType::Sqlite:
        if (!$factory->hasDriver(DatabaseType::Sqlite->value)) {
          $factory->addDriver(
            'sqlite',
            fn (array $config) => new SqliteConnection($config)
          );
        }
        return $factory->make($config->get('db.sqlite'));

      default:
        throw new Exception('you must choose default database on config file');
    }
  }
}
