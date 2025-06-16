<?php

namespace MPG; // MongoDB PHP GUI

use Nimbly\Limber\Application;
use Nimbly\Capsule\Factory\ServerRequestFactory;
use Nimbly\Limber\Exceptions\NotFoundHttpException;

const VERSION = '1.3.3';

/**
 * Absolute path, without trailing slash.
 * Example: /app
 */
const ABS_PATH = __DIR__;

session_start();

if ( !file_exists($autoload_file = ABS_PATH . '/vendor/autoload.php') ) {
    die('Run `composer install` to complete MongoDB PHP GUI installation.');
}

$loader = require_once $autoload_file;
$loader->add('MPG', ABS_PATH . '/source/php');

$router = require ABS_PATH . '/routes.php';

$application = new Application($router);
$serverRequest = ServerRequestFactory::createFromGlobals();

try {

    $response = $application->dispatch($serverRequest);
    $application->send($response);
    
} catch (NotFoundHttpException $_error) {
    die('Route not found. Try to append a slash to URL.');
}
