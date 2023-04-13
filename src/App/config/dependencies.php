<?php

use League\Flysystem\Filesystem;
use Src\App\Entities\{
  User
};
use Src\Core\Interfaces\{
  UserInterface,
  UserServiceInterface
};
use Src\Core\Middlewares\{
  AdminMiddleware,
  AuthMiddleware,
  ClockWorkMiddleware,
  CsrfMiddleware,
  DispatcherMiddleware,
  MethodMiddleware,
  RouterMiddleware,
  TrailingSlashMiddleware,
  ErrorsHandlerMiddleware,
  StartSessionMiddleware,
  TwigFlashMsgMiddleware,
  GuestMiddleware,
};
use Src\App\Services\{
  UserService
};
use Src\Core\{Config\Config};
use Src\Core\Storage\FileSystemFactory;

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
  Filesystem::class => \DI\factory(FileSystemFactory::class),

];
