<?php


namespace Src\Core\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\MiddlewareInterface;
use Src\App\Services\RequestService;
use Src\Core\Interfaces\SessionInterface;

class StartSessionMiddleware implements MiddlewareInterface
{

    public function __construct(private SessionInterface $session, private RequestService $requestService)
    {
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

        $this->session->start();
        $response = $handler->handle($request);
        if ($request->getMethod() === 'GET' && !$this->requestService->isXhr($request)) {
            $this->session->put('previousUrl', $request->getUri()->getPath());
        }
        $this->session->save();
        return $response;
    }
}