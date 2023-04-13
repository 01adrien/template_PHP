<?php


namespace Src\Core\Middlewares;

use Clockwork\Support\Vanilla\Clockwork;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\MiddlewareInterface;
use Src\Core\Router\Route;
use DI\Container;
use Src\App\Services\ResponseService;

class DispatcherMiddleware implements MiddlewareInterface
{
  public function __construct(
    private Container $container,
    private ResponseService $responseService
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

    $route = $request->getAttribute(Route::class);
    if (is_null($route)) {
      return $handler->handle($request);
    }
    if (is_callable($route->getCallback())) {
      $response  = call_user_func_array(
        $route->getCallback(),
        [$request]
      );
    }
    if (is_array($route->getCallback())) {
      [$className, $method] = $route->getCallback();
      $response = call_user_func_array(
        [$this->container->get($className), $method],
        [$request]
      );
    }
    if (is_string($response)) {
      return $this->responseService->plainTextResponse(200, $response);
    } elseif ($response instanceof Response) {
      return $response;
    } elseif (is_array($response)) {
      return $this->responseService->jsonResponse(200, $response);
    } else {
      throw new \Exception(
        'The response format is not correct'
      );
    }
  }
}
