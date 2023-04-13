<?php

namespace Src\App\Modules;

use Src\App\Controllers\AuthController;
use Src\Core\Abstracted\Module;
use Src\Core\Router\Router;

class AuthModule extends Module
{
  public function __construct(private Router $router)
  {
    $this
      ->router
      ->registerRoutesFromControllersAttributes([
        AuthController::class
      ]);
  }
}