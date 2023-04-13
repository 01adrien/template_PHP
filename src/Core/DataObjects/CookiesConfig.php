<?php

namespace Src\Core\DataObjects;

use Src\Core\Enums\SameSite;

class CookiesConfig
{
  public function __construct(
    private readonly string | bool $secure,
    private readonly string | bool $httpOnly,
    private readonly SameSite $sameSite
  ) {
  }
}
