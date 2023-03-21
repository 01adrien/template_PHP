<?php

namespace Src\Core\Middlewares;

use DI\Container;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Message\ResponseInterface;
use Src\Core\Router\Router;


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
   * Processes an incoming server request in order to produce a response.
   * If unable to produce the response itself, it may delegate to the provided
   * request handler to do so.
   *
   * @param Request $request
   * @param Handler $handler
   * @return ResponseInterface
   */
  public function process(Request $request, Handler $handler): ResponseInterface
  {
    $route = $this->container->get(Router::class)->match($request);
    if (is_null($route)) {
      return $handler->handle($request);
    }
    foreach ($route->getParams() as $key => $val) {
      $request = $request->withAttribute($key, $val);
    }
    if (is_callable($route->getCallback())) {
      return new Response(200, [], call_user_func_array(
        $route->getCallback(),
        [$request]
      ));
    }
    if (is_array($route->getCallback())) {
      [$className, $method] = $route->getCallback();
      $response = call_user_func_array(
        [$this->container->get($className), $method],
        [$request]
      );
    }
    if ($response) {
      return new Response(200, [], $response);
    } elseif ($response instanceof ResponseInterface) {
      return $response;
    } else {
      throw new \Exception('The response is not correct');
    }
  }
}
