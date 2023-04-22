<?php

namespace Src\Core\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;

class MethodMiddleware implements MiddlewareInterface
{

  /**
   * process an incoming server request
   *
   * @param  Request $request
   * @param  Handler $handler
   * @return ResponseInterface
   */
    public function process(Request $request, Handler $handler): ResponseInterface
    {
        $parsedBody = $request->getParsedBody();
        if (array_key_exists('_method', $parsedBody) &&
        in_array($parsedBody['_method'], ['DELETE', 'PUT'])
        ) {
            $request = $request->withMethod($parsedBody['_method']);
        }
        return $handler->handle($request);
    }
}
