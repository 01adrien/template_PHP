<?php

namespace Src\App\Modules;

use Src\App\Controllers\AdminController;
use Src\Core\Abstracted\Module;
use Src\Core\Router\Router;

class AdminModule extends Module
{
  public function __construct(private Router $router)
  {
    $this
      ->router
      ->registerRoutesFromControllersAttributes([
        AdminController::class
      ]);
  }
}