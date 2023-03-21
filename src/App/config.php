<?php

use Src\Core\Database\DatabaseFactory;
use Src\Core\DataStructures\StackArray;
use Src\Core\Interfaces\RendererInterface;
use Src\Core\Middlewares\{
  MiddlewareManager,
  NotFoundHandler
};


use Src\Core\Renderer\TwigRendererFactory;

return [
  'db.name' => $_ENV['DB_NAME'],
  'db.host' => $_ENV['DB_HOST'],
  'db.pass' => $_ENV['DB_PASS'],
  'db.user' => $_ENV['DB_USER'],
  MiddlewareManager::class => \DI\create()->constructor(
    new StackArray(),
    new NotFoundHandler()
  ),
  RendererInterface::class => \DI\factory(TwigRendererFactory::class),
  \PDO::class => \DI\factory(DatabaseFactory::class)
];
