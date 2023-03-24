<?php

namespace Src\App;

use Psr\Container\ContainerInterface;
use Dotenv\Dotenv;
use Psr\Http\Message\{
  ResponseInterface as Response,
  ServerRequestInterface as Request
};
use Src\Core\Middlewares\{
  TrailingSlashMiddleware,
  MiddlewareManager,
  RouterMiddleware,
  MethodMiddleware,
  DispatcherMiddleware
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
    $container = $this->getContainer();

    foreach ($modules as $module) {
      $container->get($module);
    }
  }

  public function run(Request $request): Response
  {
    $c = $this->getContainer();

    /** @var MiddlewareManager $middlewaresManager */
    $middlewaresManager = $c->get(MiddlewareManager::class);

    foreach ($c->get('base.middlewares') as $m) {
      $middlewaresManager->push($c->get($m));
    }
    return $middlewaresManager->handle($request);
  }

  public function getContainer(): ContainerInterface
  {
    if (!$this->container) {
      $builder =
        (new \DI\ContainerBuilder())
        ->addDefinitions($this->definition)
        ->addDefinitions(ROOT_DIR . '/config.php');
      $this->container = $builder->build();
    }
    return $this->container;
  }
}
