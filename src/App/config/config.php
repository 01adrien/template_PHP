<?php

use Src\Core\Enums\AppEnv;
use Src\Core\Enums\StorageDriver;

return [
  'appEnv' => $_ENV['APP_ENV'] ?? AppEnv::Production->value,
  'db' => [
    'name' => $_ENV['DB_NAME'],
    'host' => $_ENV['DB_HOST'],
    'pass' => $_ENV['DB_PASS'],
    'user' => $_ENV['DB_USER'],
  ],
  'cookies' => [
    'secure'   => $_ENV['SECURE'] ?? true,
    'httponly' => $_ENV['HTTP_ONLY'] ?? true,
    'samesite' => $_ENV['SAME_SITE'] ?? 'lax'
  ],
  'storage' => [
    'driver' => StorageDriver::local
  ],
  'logs' => [
    'php_errors' => APP_DIR . '/storage/logs/php_errors.log'
  ]
];
