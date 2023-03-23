<?php

namespace Src\App\Modules;

use Src\App\Controllers\ExempleController;
use Src\Core\Abstracted\Module;
use Src\Core\Router\Router;

class ExempleModule extends Module
{
  public function __construct(private Router $router)
  {
    $router
      ->registerRoutesFromControllersAttributes([
        ExempleController::class
      ]);
  }
}
