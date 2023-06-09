<?php

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

return
    [
        'paths' => [
            'migrations' => APP_DIR . '/database/migrations',
            'seeds' => APP_DIR . '/database/seeds'
        ],
        'environments' => [
            'default_migration_table' => 'phinxlog',
            'default_environment' => 'development',
            'development' => [
                'adapter' => 'mysql',
                'host' => $_ENV['DB_HOST'],
                'name' => $_ENV['DB_NAME'],
                'user' => $_ENV['DB_USER'],
                'pass' => $_ENV['DB_PASS'],
                'port' => '3306',
                'charset' => 'utf8',
            ],
        ],
        'version_order' => 'creation'
    ];
