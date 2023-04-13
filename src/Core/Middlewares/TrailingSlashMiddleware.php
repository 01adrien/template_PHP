<?php

namespace Src\Core\Middlewares;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class TrailingSlashMiddleware implements MiddlewareInterface
{

  /**
   * Process an incoming server request.
   *
   * Processes an incoming server request in order to produce a response.
   * If unable to produce the response itself, it may delegate to the provided
   * request handler to do so.
   *
   * @param ServerRequestInterface $request
   * @param RequestHandlerInterface $handler
   * @return ResponseInterface
   */
  public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
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
