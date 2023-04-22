<?php

namespace Src\App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface  as Request;
use Src\App\Services\ResponseService;
use Src\Core\Router\Router;
use Src\Core\DataObjects\RegisterUserData;
use Src\Core\DataObjects\Credentials;
use Src\Core\Interfaces\RendererInterface;
use Src\Core\Interfaces\AuthInterface;
use Src\Core\Attributes\Routes\Base;
use Src\Core\Attributes\Routes\Logged;
use Src\Core\Attributes\Routes\Route;

#[Base('/auth')]
class AuthController
{
    public function __construct(
        private RendererInterface $renderer,
        private Router $router,
        private AuthInterface $auth,
        private ResponseService $responseService
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
        /** @var array $body */
        $body = $request->getParsedBody();
        $data = array_filter($body, function ($item) {
            return $item !== '_csrf';
        }, ARRAY_FILTER_USE_KEY);

        $this->auth->register(new RegisterUserData(...$data));
        return $this->responseService->plainTextResponse(
            200,
            require VIEWS_DIR . '/html/pages/activationMessage.html'
        );
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
        /** @var array $body */
        $body = $request->getParsedBody();
        $data = array_filter($body, function ($item) {
            return $item !== '_csrf';
        }, ARRAY_FILTER_USE_KEY);
        $user = $this->auth->attemptLogin(new Credentials(...$data));
        if ($user) {
            $this->auth->login($user);
            return $this->router->redirect('home');
        }

        return $this->router->redirect('signin');
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

    /**
     * activate user account by sendind activation email
     *
     * @param  Request $request
     * @return Response
     */
    #[Route(path: '/activation', name: 'activation', method: 'GET')]
    public function activateAccount(Request $request): Response
    {
        /** @var array $activationKey */
        $activationKey = $request->getQueryParams();
        if (
            !count($activationKey) === 2 ||
            !array_key_exists('email', $activationKey) ||
            !array_key_exists('code', $activationKey)
        ) {
            return $this->responseService->notAuthorized403();
        }
        $user = $this->auth->validateAccountActivation($activationKey);
        $this->auth->login($user);
        return $this->responseService->plainTextResponse(
            200,
            require VIEWS_DIR . '/html/pages/activationSucces.html'
        );
    }

    /**
     * activate user account by sendind activation email
     *
     * @param  Request $request
     * @return Response
     */
    #[Route(path: '/forgot-password', name: 'forgot-password', method: 'POST')]
    public function forgotPassword(Request $request): Response
    {
        $this->auth->resetPassword($request->getParsedBody()['email']);
        return $this->router->redirect('home');
    }

    /**
     * activate user account by sendind activation email
     *
     * @return Response
     */
    #[Route(path: '/forgot-password', name: 'forgot-password-form', method: 'GET')]
    public function forgotPasswordForm(): string
    {
        return $this->renderer->render('/auth/forgotPassword');
    }
}
