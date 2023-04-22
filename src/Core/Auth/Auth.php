<?php

namespace Src\Core\Auth;

use Src\App\Entities\User;
use Src\App\Services\UserActivationService;
use Src\Core\DataObjects\Credentials;
use Src\Core\DataObjects\RegisterUserData;
use Src\Core\Exceptions\DataValidationException;
use Src\Core\Interfaces\AuthInterface;
use Src\Core\Interfaces\SessionInterface;
use Src\Core\Interfaces\UserInterface;
use Src\Core\Interfaces\UserServiceInterface;
use Src\Core\Exceptions\AuthException;
use Src\Core\Exceptions\SessionException;
use Src\Core\Exceptions\UserActivationException;

class Auth implements AuthInterface
{
    public function __construct(
        private UserServiceInterface $userService,
        private SessionInterface $session,
        private UserActivationService $userActivationService
    ) {
    }
    /**
     * return current user
     *
     * @return UserInterface
     */
    public function currentUser(): ?UserInterface
    {
        if ($this->session->has('user')) {
            /** @var UserInterface $user */
            $user = $this
                ->userService
                ->getById($this->session->get('user'));
            return $user->getUserSafe();
        }
        return null;
    }

    /**
     * Check credentials for login
     *
     * @param  Credentials $credentials
     * @return UserInterface
     * @throws \Exception
     */
    public function attemptLogin(Credentials $credentials): UserInterface
    {
        [$user] = $this->userService->getByEmail($credentials->email);
        if (
            $user && $user->active &&
            password_verify($credentials->password, $user->getPassword())
        ) {
            return $user;
        }
        throw new AuthException(
            [
                'credentials' => '⚠️ wrong credentials or inactive user ⚠️'
            ]
        );
    }

    /**
     * login
     *
     * @param  UserInterface $user
     * @throws \Exception
     */
    public function login(UserInterface $user): void
    {
        if (!$this->session->put('user', $user->getId())) {
            throw new SessionException(
                [
                    'session' => '⚠️ internal error try later ⚠️'
                ]
            );
        }
        $this->session->regenerateID();
    }

    /**
     * register a new user and log in him
     *
     * @param  RegisterUserData $data
     * @return UserInterface
     */
    public function register(RegisterUserData $data): UserInterface
    {
        /** @var UserInterface $user */
        $user = $this->userService->createUser($data);
        $this->userActivationService->handle($user);
        return $user;
    }

    /**
     * logOut the current user
     *
     * @return void
     */
    public function logOut(): void
    {
        $this->session->forget('user');
        $this->session->regenerateID();
    }

    /**
     * validate Account Activation
     *
     * @param  array $params
     * @return UserInterface
     */
    public function validateAccountActivation(array $params): UserInterface
    {
        /** @var User $user  */
        $user = $this->userService->getByEmail($params['email']);
        if (!$user || $user->active) {
            throw new UserActivationException(['msg' => 'not allowed']);
        }
        $this->userActivationService->validateAccountActivation($user, $params['code']);
        $this->login($user);
        return $user;
    }

    public function resetPassword(string $email): void
    {
        /** @var User $user  */
        $user = $this->userService->getByEmail($email);
        if (!$user || !$user->active) {
            throw new DataValidationException(['msg' => 'user not found or not active']);
        }
        $this->userService->resetPassword($user);
    }
}
