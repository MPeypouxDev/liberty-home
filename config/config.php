<?php
// Configuration générale de l'application

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('Europe/Paris');

define('BASE_URL', 'http://localhost/Projets/liberty-home/');
define('ASSETS_URL', BASE_URL . 'assets/');

define('ROOT_PATH', dirname(__DIR__) . '/');
define('UPLOAD_PATH', ROOT_PATH . 'assets/images/uploads/');

define('HASH_ALGORITHM', PASSWORD_DEFAULT);

error_reporting(E_ALL);
ini_set('display_errors', 1);