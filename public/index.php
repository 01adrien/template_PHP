<?php

use GuzzleHttp\Psr7\ServerRequest;
use Src\App\App;
use Src\App\Modules\{
  AdminModule,
  AuthModule,
  ExempleModule,
  UserModule
};


require dirname(__DIR__) . '/src/App/config/pathsConstants.php';

require ROOT_DIR . '/vendor/autoload.php';

require APP_DIR   . '/config/errorsConfig.php';

$app = new App(
  modules: [
    ExempleModule::class,
    AuthModule::class,
    AdminModule::class,
    UserModule::class,
  ],
  dependencies: [
    APP_DIR . '/config/dependencies.php',
    CORE_DIR . '/Config/dependencies.php',
    ROOT_DIR . '/config.php'
  ]
);

$response = $app->run(ServerRequest::fromGlobals());

\Http\Response\send($response);
