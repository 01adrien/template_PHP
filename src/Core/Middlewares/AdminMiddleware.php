<?php

namespace Src\Core\Middlewares;

use Psr\Container\ContainerInterface;
use ReflectionClass;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Src\Core\Attributes\Routes\Admin;
use Src\Core\Auth\Auth;
use Src\Core\Enums\Role;
use Src\Core\Router\Route;
use Src\Core\Router\Router;

class AdminMiddleware implements MiddlewareInterface
{
    public function __construct(
        private ContainerInterface $container,
        private Auth $auth
    ) {
    }
  /**
   * process an incoming server request
   *
   * @param  Request $request
   * @param  Handler $handler
   * @return Response
   */
    public function process(Request $request, Handler $handler): Response
    {
        $router = $this->container->get(Router::class);
        $route = $router->match($request);
        $currentUser = $request->getAttribute('user');
        if ($currentUser?->role !== Role::Admin->value &&
        $route && $this->isAdminRoute($route)
        ) {
            return $router->redirect('signin');
        }
        return $handler->handle($request);
    }

    private function isAdminRoute(Route $route): bool
    {
        [$className, $method] = $route->getCallback();
        $reflectionClass = new ReflectionClass($className);
        $reflectionMethod = $reflectionClass->getMethod($method);
        $attributes = $reflectionMethod->getAttributes(
            Admin::class,
            \ReflectionAttribute::IS_INSTANCEOF
        );
        if (!empty($attributes)) {
            return true;
        }
        return false;
    }
}