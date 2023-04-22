<?php

use Src\App\Entities\User;
use Src\Core\Interfaces\UserInterface;
use Src\Core\Interfaces\UserServiceInterface;
use Src\Core\Middlewares\AdminMiddleware;
use Src\Core\Middlewares\AuthMiddleware;
use Src\Core\Middlewares\ClockWorkMiddleware;
use Src\Core\Middlewares\CsrfMiddleware;
use Src\Core\Middlewares\DispatcherMiddleware;
use Src\Core\Middlewares\MethodMiddleware;
use Src\Core\Middlewares\RouterMiddleware;
use Src\Core\Middlewares\TrailingSlashMiddleware;
use Src\Core\Middlewares\ErrorsHandlerMiddleware;
use Src\Core\Middlewares\StartSessionMiddleware;
use Src\Core\Middlewares\TwigFlashMsgMiddleware;
use Src\Core\Middlewares\GuestMiddleware;
use Src\App\Services\UserService;
use Src\Core\Config\Config;

return [
  'middlewares' => [
    DispatcherMiddleware::class,
    ClockWorkMiddleware::class,
    TwigFlashMsgMiddleware::class,
    RouterMiddleware::class,
    CsrfMiddleware::class,
    MethodMiddleware::class,
    AdminMiddleware::class,
    GuestMiddleware::class,
    AuthMiddleware::class,
    StartSessionMiddleware::class,
    ErrorsHandlerMiddleware::class,
    TrailingSlashMiddleware::class,
  ],
  Config::class => \DI\create()->constructor(APP_DIR . '/config/config.php'),
  UserInterface::class => \DI\autowire(User::class),
  UserServiceInterface::class => \DI\autowire(UserService::class),

];
