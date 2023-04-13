<?php

namespace Src\Core\Auth;

use Src\Core\DataObjects\{
  Credentials,
  RegisterUserData
};
use Src\Core\Interfaces\{
  AuthInterface,
  SessionInterface,
  UserInterface,
  UserServiceInterface
};
use Src\Core\Exceptions\{
  AuthException,
  SessionException
};

class Auth implements AuthInterface
{
  public function __construct(
    private UserServiceInterface $userService,
    private SessionInterface $session
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
      $user &&
      password_verify($credentials->password, $user->getPassword())
    ) {
      return $user;
    }
    throw new AuthException(
      [
        'credentials' => '⚠️ wrong credentials ⚠️'
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
   * @param  RegisterUserData $credentials
   * @return UserInterface
   */
  public function register(RegisterUserData $data): UserInterface
  {
    $user = $this->userService->createUser($data);
    $this->login($user);
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
}
