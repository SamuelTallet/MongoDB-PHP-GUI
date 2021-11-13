<?php

namespace MPG;

use Limber\Application;
use Capsule\Factory\ServerRequestFactory;
use Limber\Exceptions\NotFoundHttpException;

const APP_NAME = 'MongoDB PHP GUI';
const VERSION = '1.2.7';

/**
 * Absolute path, without trailing slash.
 * Example: /opt/mongodb-php-gui
 */
const ABS_PATH = __DIR__;

session_start();

if ( !file_exists($autoload_file = ABS_PATH . '/vendor/autoload.php') ) {
    die('Run `composer install` to complete ' . APP_NAME . ' installation.');
}

$loader = require_once $autoload_file;
$loader->add('MPG', ABS_PATH . '/source/php');

Request::initialize();

$baseUrl = '//' . $_SERVER['HTTP_HOST'] . Request::getPath();

/**
 * Base URL, without trailing slash.
 * 
 * @var string
 * Example: //127.0.0.1:5000/mongo
 */
define('MPG\BASE_URL', $baseUrl);

$router = require ABS_PATH . '/routes.php';

$application = new Application($router);
$serverRequest = ServerRequestFactory::createFromGlobals();

try {

    $response = $application->dispatch($serverRequest);
    $application->send($response);
    
} catch (NotFoundHttpException $_error) {
    die('Route not found. Try to append a slash to URL.');
}
