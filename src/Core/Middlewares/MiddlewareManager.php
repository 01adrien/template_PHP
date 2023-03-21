<?php

namespace Src\Core\Middlewares;

use Src\Core\Interfaces\StackInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class MiddlewareManager implements RequestHandlerInterface
{
  /**
   * __construct the middleware manager with the stack datastructure and the last handler
   *
   * @param StackInterface $stack
   * @param RequestHandlerInterface $fallback
   */
  public function __construct(
    public StackInterface $stack,
    private RequestHandlerInterface $fallback
  ) {
  }

  /**
   * push a middleware into the stack
   *
   * @param MiddlewareInterface $middleware
   * @return self
   */
  public function push(MiddlewareInterface $middleware): self
  {
    $this->stack->push($middleware);
    return $this;
  }

  /**
   * Handles a request and produces a response.
   * May call other collaborating code to generate the response.
   *
   * @param ServerRequestInterface $request
   * @return ResponseInterface
   */
  public function handle(ServerRequestInterface $request): ResponseInterface
  {
    if (!$this->stack->length()) {
      return $this->fallback->handle($request);
    }
    $middleware = $this->stack->pop();
    return $middleware->process($request, $this);
  }

  public function getStack()
  {
    return $this->stack;
  }
}
