<?php

namespace Src\Core\Middlewares;

use DI\Container;
use ReflectionClass;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Src\Core\Attributes\Routes\Logged;
use Src\Core\Auth\Auth;
use Src\Core\Interfaces\RendererInterface;
use Src\Core\Router\{Route, Router};

class AuthMiddleware implements MiddlewareInterface
{
  public function __construct(
    private Container $container,
    private Auth $auth,
    private RendererInterface $renderer
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
    $currentUser = $this->auth->currentUser();
    if (
      !$currentUser && $route &&
      $this->isProtectedRoute($route)
    ) {
      return $router->redirect('signin');
    }
    $this->renderer->addGlobal('user', $currentUser);
    return $handler->handle($request->withAttribute('user', $currentUser));
  }

  private function isProtectedRoute(Route $route): bool
  {
    [$className, $method] = $route->getCallback();
    $reflectionClass = new ReflectionClass($className);
    $reflectionMethod = $reflectionClass->getMethod($method);
    $attributes = $reflectionMethod->getAttributes(
      Logged::class,
      \ReflectionAttribute::IS_INSTANCEOF
    );
    if (!empty($attributes)) return true;
    return false;
  }
}
