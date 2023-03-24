<?php

namespace Src\Core\Middlewares;

use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\MiddlewareInterface;

class TrailingSlashMiddleware implements MiddlewareInterface
{

  /**
   * Process an incoming server request.
   *
   * @param Request $request
   * @param Handler $handler
   * @return Response
   */
  public function process(Request $request, Handler $handler): Response
  {
    $uri = $request->getUri()->getPath();
    if (!empty($uri) && $uri[-1] === "/" && strlen($uri) > 1) {
      return (new \GuzzleHttp\Psr7\Response())
        ->withStatus(301)
        ->withHeader('Location', substr($uri, 0, -1));
    }
    return $handler->handle($request);
  }
}
