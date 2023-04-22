<?php

namespace Src\App;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Src\Core\Middlewares\MiddlewareManager;
use Dotenv\Dotenv;

class App
{
    private ?ContainerInterface $container = null;

    private static ?App $instance = null;


    public function __construct(private array $modules, private array $dependencies)
    {
        $dotenv = Dotenv::createImmutable(ROOT_DIR);
        $dotenv->load();
        $c = $this->getContainer();

        foreach ($modules as $module) {
            $c->get($module);
        }
    }

    public function run(Request $request): Response
    {
        $c = $this->getContainer();

        $middlewaresManager = $c->get(
            MiddlewareManager::class
        );
        foreach ($c->get('middlewares') as $m) {
            $middlewaresManager->push($c->get($m));
        }
        return $middlewaresManager->handle($request);
    }

    public function getContainer(): ContainerInterface
    {
        if (!$this->container) {
            $builder = new \DI\ContainerBuilder();
            foreach ($this->dependencies as $dep) {
                $builder->addDefinitions($dep);
            }
            $this->container = $builder->build();
        }
        return $this->container;
    }

    public static function getInstance(array $dependencies): self
    {
        if (!self::$instance) {
            self::$instance = new App([], $dependencies);
        }
        return self::$instance;
    }
}
