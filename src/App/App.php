<?php

namespace Src\App;

use Dotenv\Dotenv;
use Psr\Container\ContainerInterface;
use Src\Core\Router\Router;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Src\App\Controllers\ExempleController;
use Src\Core\Middlewares\{
  TrailingSlashMiddleware,
  MiddlewareManager,
  RouterMiddleware
};


class App
{
  private ?ContainerInterface $container = null;

  public function __construct(
    private array $modules,
    private string $definition,
  ) {
    $dotenv = Dotenv::createImmutable(ROOT_DIR);
    $dotenv->load();
  }

  public function run(Request $request): Response
  {
    $container = $this->getContainer();
    $container
      ->get(Router::class)
      ->registerRoutesFromControllersAttributes([
        ExempleController::class
      ]);

    return
      $container
      ->get(MiddlewareManager::class)
      ->push($container->get(RouterMiddleware::class))
      ->push($container->get(TrailingSlashMiddleware::class))
      ->handle($request);
  }

  public function getContainer(): ContainerInterface
  {
    if (!$this->container) {
      $builder = new \DI\ContainerBuilder();
      $builder->addDefinitions($this->definition);
      $builder->addDefinitions(ROOT_DIR . '/config.php');
      $this->container = $builder->build();
    }
    return $this->container;
  }
}
