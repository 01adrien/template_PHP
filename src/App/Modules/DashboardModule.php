<?php

namespace Src\App\Modules;

use Src\App\Controllers\DashboardController;
use Src\Core\Router\Router;

class DashboardModule
{
  public function __construct(private Router $router)
  {
    $router
      ->registerRoutesFromControllersAttributes([
        DashboardController::class
      ]);
  }
}
