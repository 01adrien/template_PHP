<?php

namespace Src\Core\Database;

use PDO;
use Psr\Container\ContainerInterface;

class DatabaseFactory
{

  public function __invoke(ContainerInterface $container)
  {
    return new PDO(
      'mysql:host=' . $container->get('db.host')
        . ';dbname=' . $container->get('db.name'),
      $container->get('db.user'),
      $container->get('db.pass'),
      [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
      ]
    );
  }
}
