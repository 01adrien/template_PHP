<?php

use Src\Core\Enums\AppEnv;
use Src\Core\Enums\CacheType;
use Src\Core\Enums\DatabaseType;
use Src\Core\Enums\StorageDriver;
use Src\Core\Enums\QueueSystem;
use Src\Core\Enums\LoggerSystem;
use Src\Core\Enums\MailerSystem;

return [
  'appEnv' => $_ENV['APP_ENV'] ?? AppEnv::Production->value,
  'baseUrl' => $_ENV['BASE_URL'],
  'database' => [
    'default' => DatabaseType::Mysql,
    'mysql'  => [
      'type' => DatabaseType::Mysql->value,
      'port' => $_ENV['DB_PORT'],
      'name' => $_ENV['DB_NAME'],
      'host' => $_ENV['DB_HOST'],
      'pass' => $_ENV['DB_PASS'],
      'user' => $_ENV['DB_USER'],
    ],
    'sqlite' => [
      'type' => DatabaseType::Mysql->value,
      'path' => ROOT_DIR . '/database.sqlite',
    ]
  ],
  'cookies' => [
    'secure'   => $_ENV['SECURE'] ?? true,
    'httponly' => $_ENV['HTTP_ONLY'] ?? true,
    'samesite' => $_ENV['SAME_SITE'] ?? 'lax'
  ],
  'storage' => [
    'default' => StorageDriver::Local,
    'local'   => [
      'type' => StorageDriver::Local->value,
      'path' => STORAGE_DIR,
    ],
    's3' => [
      'type'   => StorageDriver::S3->value,
      'key'    => $_ENV['S3_KEY'],
      'secret' => $_ENV['S3_SECRET'],
      'token'  => $_ENV['S3_TOKEN'],
      'region' => $_ENV['S3_REGION'],
      'bucket' => $_ENV['S3_BUCKET'],
    ],
    'ftp' => [
      'type' => StorageDriver::Ftp->value,
      'host' => $_ENV['FTP_HOST'],
      'root' => $_ENV['FTP_ROOT'],
      'user' => $_ENV['FTP_USER'],
      'pass' => $_ENV['FTP_PASS'],
    ],
  ],
  'logs' => [
    'php_errors' => APP_DIR . '/storage/logs/php_errors.log'
  ],
  'cache' => [
    'default' => CacheType::Redis,
    'memory' => [
      'type'    => CacheType::Memory->value,
      'seconds' => $_ENV['CACHE_EXPIRE'],
    ],
    'file' => [
      'type'    => CacheType::File->value,
      'seconds' => $_ENV['CACHE_EXPIRE'],
    ],
    'memcache' => [
      'type'    => CacheType::Memcache->value,
      'host'    => $_ENV['MEMCACHE_HOST'],
      'port'    => $_ENV['MEMCACHE_PORT'],
      'seconds' => $_ENV['CACHE_EXPIRE'],
    ],
    'redis' => [
      'type'     => CacheType::Redis->value,
      'host'     => $_ENV['REDIS_HOST'],
      'port'     => $_ENV['REDIS_PORT'],
      'pass'     => $_ENV['REDIS_PASS'],
      'seconds'  => $_ENV['CACHE_EXPIRE'],
      'database' => 0,
    ],
  ],
  'queue' => [
    'default' => QueueSystem::Redis,
    'database' => [
      'type'     => QueueSystem::Database->value,
      'table'    => 'jobs',
      'attempts' => 3,
    ],
    'redis' => [
      'type'     => QueueSystem::Redis->value,
      'host'     => $_ENV['REDIS_HOST'],
      'port'     => $_ENV['REDIS_PORT'],
      'pass'     => $_ENV['REDIS_PASS'],
      'database' => 1,
      'attempts' => 3,
    ]
  ],
  'logger' => [
    'default' => LoggerSystem::Stream,
    'stream'    => [
      'type'    => LoggerSystem::Stream->value,
      'path'    => STORAGE_DIR . '/logs/app.log',
      'name'    => 'App',
      'minimum' => \Monolog\Level::Debug,
    ],
  ],
  'mailer' => [
    'default' => MailerSystem::Symphony,
    'symphony' => [
      'type' => MailerSystem::Symphony->value,
      'host' => $_ENV['APP_ENV'] === AppEnv::Development->value ? $_ENV['MAILTRAP_HOST'] : $_ENV['MAILER_HOST'],
      'port' => $_ENV['APP_ENV'] === AppEnv::Development->value ? $_ENV['MAILTRAP_PORT'] : $_ENV['MAILER_PORT'],
      'user' => $_ENV['APP_ENV'] === AppEnv::Development->value ? $_ENV['MAILTRAP_USER'] : $_ENV['MAILER_USER'],
      'pass' => $_ENV['APP_ENV'] === AppEnv::Development->value ? $_ENV['MAILTRAP_PASS'] : $_ENV['MAILER_PASS'],
    ],
    'php' => [
      'type' => MailerSystem::Php->value,
      'host' => $_ENV['APP_ENV'] === AppEnv::Development->value ? $_ENV['MAILTRAP_HOST'] : $_ENV['MAILER_HOST'],
      'port' => $_ENV['APP_ENV'] === AppEnv::Development->value ? $_ENV['MAILTRAP_PORT'] : $_ENV['MAILER_PORT'],
      'user' => $_ENV['APP_ENV'] === AppEnv::Development->value ? $_ENV['MAILTRAP_USER'] : $_ENV['MAILER_USER'],
      'pass' => $_ENV['APP_ENV'] === AppEnv::Development->value ? $_ENV['MAILTRAP_PASS'] : $_ENV['MAILER_PASS'],
    ]
  ]
];