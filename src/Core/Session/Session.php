<?php

namespace Src\Core\Session;

use Src\Core\Config\Config;
use Src\Core\DataObjects\CookiesConfig;
use Src\Core\Exceptions\SessionException;
use Src\Core\Interfaces\SessionInterface;

class Session implements SessionInterface
{

  public function __construct(private readonly CookiesConfig $cookieConfig)
  {
  }
  public function start(): void
  {
    if ($this->isActive()) {
      throw new SessionException(
        ['session' => 'Session has already been started']
      );
    }
    if (headers_sent($fileName, $line)) {
      throw new SessionException(
        ['header' => "Headers already sent by"]
      );
    }

    // session_set_cookie_params($this->config->get('cookies'));

    if (!session_start()) {
      throw new SessionException([
        'session' => 'Unable to start the session'
      ]);
    };
  }

  public function save(): void
  {
    session_write_close();
  }

  public function isActive(): bool
  {
    return session_status() === PHP_SESSION_ACTIVE;
  }

  public function forget(string $key): void
  {
    unset($_SESSION[$key]);
  }

  public function put(string $key, mixed $value): bool
  {
    if ($_SESSION[$key] = $value) return true;
    return false;
  }

  public function has(string $key): bool
  {
    return array_key_exists($key, $_SESSION ?? []);
  }

  public function regenerateID(): void
  {
    session_regenerate_id();
  }

  public function get(string $key, mixed $default = null): mixed
  {
    return $this->has($key) ? $_SESSION[$key] : $default;
  }
}
