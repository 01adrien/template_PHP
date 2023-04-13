<?php

namespace Src\Core\Middlewares;

use PDOException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Server\MiddlewareInterface;
use Src\App\Exceptions\ReceiptException;
use Src\App\Exceptions\TransactionsException;
use Src\App\Services\{RequestService, ResponseService};
use Src\Core\Config\Config;
use Src\Core\Enums\AppEnv;
use Src\Core\Exceptions\{
  AuthException,
  CsrfInvalidException,
  SessionException,
  CategoriesException,
  DataValidationException
};
use Src\Core\Exceptions\FileUploadException;
use Src\Core\Interfaces\SessionInterface;
use Src\Core\Router\Router;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class ErrorsHandlerMiddleware implements MiddlewareInterface
{
  public function __construct(
    private Router $router,
    private SessionInterface $session,
    private RequestService $requestService,
    private ResponseService $responseService,
    private CsrfMiddleware $csrfMiddleware,
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


    $sensitivesData = ['password', 'confirm_password'];

    $oldData  = array_filter(
      $request->getParsedBody(),
      function ($field) use ($sensitivesData) {
        return !in_array($field, $sensitivesData);
      },
      ARRAY_FILTER_USE_KEY
    );
    try {
      return $handler->handle($request);
    } catch (
      AuthException |
      SessionException |
      TransactionsException |
      CategoriesException |
      DataValidationException |
      ReceiptException |
      FileUploadException $e
    ) {
      if (!$this->requestService->isXhr($request)) {
        if ($oldData) $this->session->put('old', $oldData);
        $this->session->put('errors', $e->errors);
        return $this->router->redirectByPath($this->session->get('previousUrl') ?? 'signin');
      } else {
        return $this->responseService->jsonResponse(
          422,
          [
            'error' => $e->errors,
            'csrf' => $this->csrfMiddleware->generateToken()
          ]
        );
      }
    } catch (CsrfInvalidException $e) {
      return $this->responseService->jsonResponse(
        403,
        [
          'error' => $e->errors,
        ]
      );
    } catch (PDOException $e) {
      if (AppEnv::isDevelopment($this->config->get('appEnv'))) {
        return $this->responseService->jsonResponse(
          500,
          [
            'error' => $e->getMessage(),
          ]
        );
      }
      return $handler->handle($request);
    } catch (\Throwable $e) {
      if (AppEnv::isDevelopment($this->config->get('appEnv'))) {
        $whoops = new Run();
        $whoops->pushHandler(new PrettyPageHandler());
        $whoops->register();
        throw $e;
      }
      return $handler->handle($request);
    }
  }
}
