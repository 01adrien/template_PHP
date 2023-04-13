<?php

namespace Src\App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface  as Request;
use Src\Core\Router\Router;
use Src\Core\DataObjects\{
  RegisterUserData,
  Credentials
};
use Src\Core\Interfaces\{
  RendererInterface,
  AuthInterface
};
use Src\Core\Attributes\Routes\{
  Base,
  Logged,
  Route
};

#[Base('/auth')]
class AuthController
{
  public function __construct(
    private RendererInterface $renderer,
    private Router $router,
    private AuthInterface $auth,
  ) {
  }

  /**
   * Display the sigin page
   *
   * @return string
   */
  #[Route(path: '/signin', name: 'signin', method: 'GET')]
  public function signin(): string
  {
    return $this->renderer->render('auth/signin');
  }

  /**
   * Dispaly the login page
   *
   * @return string
   */
  #[Route(path: '/signup', name: 'signup', method: 'GET')]
  public function signup(): string
  {
    return $this->renderer->render('auth/signup');
  }

  /**
   * Create a user and log him in
   *
   * @param  Request $request
   * @return Response
   */
  #[Route(path: '/register', name: 'register', method: 'POST')]
  public function register(Request $request): Response
  {
    $body = $request->getParsedBody();
    unset($body['_csrf']);
    $this->auth->register(new RegisterUserData(...$body));
    return $this->router->redirect('dashboard');
  }

  /**
   * Log a user in
   *
   * @param  Request $request
   * @return Response
   */
  #[Route(path: '/login', name: 'login', method: 'POST')]
  public function login(Request $request): Response
  {
    $body = $request->getParsedBody();
    unset($body['_csrf']);
    $user = $this->auth->attemptLogin(new Credentials(...$body));

    if ($user) {
      $this->auth->login($user);
      return $this->router->redirect('dashboard');
    }
    return $this->router->redirect('login');
  }

  /**
   * logOut the current user
   *
   * @return Response
   */
  #[Logged]
  #[Route(path: '/logout', name: 'logout', method: 'POST')]
  public function logOut(): Response
  {
    $this->auth->logOut();
    return $this->router->redirect('signin');
  }
}
