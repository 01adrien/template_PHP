<?php

namespace Src\Core\Middlewares;

use Clockwork\Support\Vanilla\Clockwork;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Src\Core\Config\Config;
use Src\Core\Enums\AppEnv;

class ClockWorkMiddleware implements MiddlewareInterface
{
    public function __construct(
        private Clockwork $clockwork,
        private Config $config
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
        if (AppEnv::isDevelopment($this->config->get('appEnv'))) {
            return $this
            ->clockwork
            ->usePsrMessage($request, $handler->handle($request))
            ->requestProcessed();
        }
        return $handler->handle($request);
    }
}
