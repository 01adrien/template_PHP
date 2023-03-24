<?php

namespace Src\Core\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface;

class NotFoundHandler implements RequestHandlerInterface
{
  /**
   * Handles a request and produces a response.
   *
   * @param Request $request
   * @return Response
   */
  public function handle(Request $request): Response
  {
    return new \GuzzleHttp\Psr7\Response(404, [], require VIEWS_DIR . '/404.twig');
  }
}
