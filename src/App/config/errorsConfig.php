<?php

use Dotenv\Dotenv;
use Src\Core\Config\Config;

$dotenv = Dotenv::createImmutable(ROOT_DIR);
$dotenv->load();

$config = new Config(APP_DIR . '/config/config.php');

error_reporting(E_ALL);
ini_set('ignore_repeated_errors', TRUE);
ini_set('display_errors', TRUE);
ini_set('log_errors', TRUE);
ini_set('error_log', $config->get('logs.php_errors'));
