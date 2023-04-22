<?php

use Dotenv\Dotenv;
use Src\Core\Config\Config;

$dotenv = Dotenv::createImmutable(ROOT_DIR);
$dotenv->load();

$config = new Config(APP_DIR . '/config/config.php');

error_reporting(E_ALL);
ini_set('ignore_repeated_errors', true);
ini_set('display_errors', true);
ini_set('log_errors', true);
ini_set('error_log', $config->get('logs.php_errors'));
