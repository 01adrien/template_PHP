<?php

namespace Src\App\Modules;

use Src\App\Controllers\UserController;
use Src\Core\Abstracted\Module;
use Src\Core\Router\Router;

class UserModule extends Module
{
    public function __construct(private Router $router)
    {
        $this
        ->router
        ->registerRoutesFromControllersAttributes([
        UserController::class
        ]);
    }
}
