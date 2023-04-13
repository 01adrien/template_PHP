<?php

namespace Src\Core\Middlewares;


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Src\Core\Exceptions\CsrfInvalidException;
use Src\Core\Interfaces\SessionInterface;

class CsrfMiddleware implements MiddlewareInterface
{


  public function __construct(
    private SessionInterface &$session,
    private int $limit = 2000,
    private string $formKey = '_csrf',
    private string $sessionKey = 'csrf'
  ) {
    //$this->validSession($session);

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


    $body2 = $request->getParsedBody();

    if (in_array($request->getMethod(), ['POST', 'PUT', 'DELETE'])) {
      $body = $request->getParsedBody() ?: [];
      if (!array_key_exists($this->formKey, $body)) {
        $this->reject();
      } else {
        $csrfList = $this->session->get($this->sessionKey) ?? [];
        if (in_array($body[$this->formKey], $csrfList)) {
          $this->useToken($body[$this->formKey]);
          return $handler->handle($request);
        } else {
          $this->reject();
        }
      }
    } else {
      return $handler->handle($request);
    }
  }

  public function generateToken(): string
  {
    $token = bin2hex(random_bytes(16));
    $csrfList = $this->session->get($this->sessionKey) ?? [];
    $csrfList[] = $token;
    $this->session->put($this->sessionKey, $csrfList);
    $this->limitTokens();
    return $token;
  }

  private function reject(): void
  {
    throw new CsrfInvalidException(
      [
        'msg' => 'invalid csrf token'
      ]
    );
  }

  private function useToken($token): void
  {
    $tokens = array_filter(
      $this->session->get($this->sessionKey),
      function ($t) use ($token) {
        return $token !== $t;
      }
    );
    $this->session->put($this->sessionKey, $tokens);
  }

  private function limitTokens(): void
  {
    $tokens = $this->session->get($this->sessionKey) ?? [];
    if (count($tokens) > $this->limit) {
      array_shift($tokens);
    }
    $this->session->put($this->sessionKey, $tokens);
  }

  /**
   * @return string
   */
  public function getFormKey(): string
  {
    return $this->formKey;
  }
}
