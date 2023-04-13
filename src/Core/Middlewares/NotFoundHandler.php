<?php

namespace Src\Core\Middlewares;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Src\App\Services\RequestService;
use Src\App\Services\ResponseService;

class NotFoundHandler implements RequestHandlerInterface
{

  public function __construct(
    private RequestService $requestService,
    private ResponseService $responseService
  ) {
  }
  /**
   * Handles a request and produces a response.
   *
   * May call other collaborating code to generate the response.
   *
   * @param ServerRequestInterface $request
   * @return ResponseInterface
   */
  public function handle(ServerRequestInterface $request): ResponseInterface
  {
    $path = $request->getUri()->getPath();
    if ($this->requestService->isXhr($request)) {
      return $this->responseService->jsonResponse(404, ['msg' => "end point ' $path ' not found"]);
    }
    return $this->responseService->plainTextResponse(404, require VIEWS_DIR . '/errors/404.html');
  }
}