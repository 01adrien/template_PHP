<?php

use GuzzleHttp\Psr7\ServerRequest;
use Src\App\App;
use Src\App\Modules\{
  DashboardModule,
  ExempleModule
};

define('ROOT_DIR', dirname(__DIR__));
define('APP_DIR', dirname(__DIR__) . '/src/App');
define('CORE_DIR', dirname(__DIR__) . '/src/Core');
define('VIEWS_DIR', dirname(__DIR__) . '/src/App/views');
define('BUILD_DIR', __DIR__ . '/build');

require ROOT_DIR . '/vendor/autoload.php';

$response = (new App([
  ExempleModule::class,
  DashboardModule::class
], APP_DIR . '/config.php'))
  ->run(ServerRequest::fromGlobals());

\Http\Response\send($response);
