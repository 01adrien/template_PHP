<?php

namespace Src\Core\Middlewares;

use DI\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Src\Core\Interfaces\SessionInterface;
use Src\Core\Router\Router;

class GuestMiddleware implements MiddlewareInterface
{
    public function __construct(
        private Container $container,
        private SessionInterface $session
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

        $onlyGuestPage = ['signin', 'signup'];

        if ($this->session->has('user') &&
        in_array($route?->getName(), $onlyGuestPage)

        ) {
            return $router->redirect('home');
        }
        return $handler->handle($request);
    }
}
