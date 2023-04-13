<?php

namespace Src\Core\Database;

use PDO;
use Src\Core\Config\Config;

class DatabaseFactory
{

  public function __invoke(Config $config)
  {
    return new PDO(
      'mysql:host=' . $config->get('db.host')
        . ';dbname=' . $config->get('db.name'),
      $config->get('db.user'),
      $config->get('db.pass'),
      [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
      ]
    );
  }
}
