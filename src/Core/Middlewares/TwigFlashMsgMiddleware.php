<?php


namespace Src\Core\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\MiddlewareInterface;
use Src\Core\Interfaces\RendererInterface;
use Src\Core\Interfaces\SessionInterface;

class TwigFlashMsgMiddleware implements MiddlewareInterface
{
  public function __construct(
    private RendererInterface $renderer,
    private SessionInterface $session
  ) {
  }
  /**
   * process an incoming server request
   *
   * @param  Request $request`
   * @param  Handler $handler
   * @return Response
   */
  public function process(Request $request, Handler $handler): Response
  {
    if ($this->session->has('errors')) {
      $errors = $this->session->get('errors');
      $this->renderer->addGlobal('errors', $errors);
      $this->session->forget('errors');
    }
    if ($this->session->has('old')) {
      $old = $this->session->get('old');
      $this->renderer->addGlobal('old', $old);
      $this->session->forget('old');
    }
    if ($this->session->has('succes')) {
      $succes = $this->session->get('succes');
      $this->renderer->addGlobal('succes', $succes);
      $this->session->forget('succes');
    }
    if (array_key_exists('search', $request->getQueryParams())) {
      $this->renderer->addGlobal('is_search', true);
    }

    $this->renderer->addGlobal(
      'query_params',
      $request->getQueryParams()
    );
    return $handler->handle($request);
  }
}
