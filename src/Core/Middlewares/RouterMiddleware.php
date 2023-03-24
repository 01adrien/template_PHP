<?php

namespace Src\Core\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\MiddlewareInterface;
use Src\Core\Router\Router;
use DI\Container;


class RouterMiddleware implements MiddlewareInterface
{

  /**
   * @var Router
   */

  public function __construct(private Container $container)
  {
  }
  /**
   * Process an incoming server request.
   *
   * @param Request $request
   * @param Handler $handler
   * @return Response
   */
  public function process(Request $request, Handler $handler): Response
  {
    $route = $this->container->get(Router::class)->match($request);
    if (is_null($route)) {
      return $handler->handle($request);
    }
    foreach ($route->getParams() as $key => $val) {
      $request = $request->withAttribute($key, $val);
    }
    $request = $request->withAttribute(get_class($route), $route);

    return $handler->handle($request);
  }
}
