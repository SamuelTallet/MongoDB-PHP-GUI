<?php

use Limber\Application;
use Capsule\Factory\ServerRequestFactory;
use Limber\Exceptions\NotFoundHttpException;

session_start();

/**
 * Application name.
 * 
 * @var string
 */
define('MPG_APP_NAME', 'MongoDB PHP GUI');

/**
 * Application version.
 * 
 * @var string
 */
define('MPG_APP_VERSION', '1.2.1');

/**
 * Development mode?
 * 
 * @var boolean
 */
define('MPG_DEV_MODE', false);

/**
 * Absolute path. XXX Without trailing slash.
 * 
 * @var string
 * @example /opt/mongodb-php-gui
 */
define('MPG_ABS_PATH', __DIR__);

$baseUrl = '//' . $_SERVER['HTTP_HOST'];

// If request matches a folder. For example: /mongo/
if ( preg_match('#/$#', $_SERVER['REQUEST_URI']) ) {

    $serverPath = $_SERVER['REQUEST_URI'];

} else {

    $serverPath = dirname($_SERVER['REQUEST_URI']);

    // Normalize directory separator in server path.
    if ( DIRECTORY_SEPARATOR !== '/' ) {
        $serverPath = str_replace(DIRECTORY_SEPARATOR, '/', $serverPath);
    }

}

$serverPath = rtrim($serverPath, '/');

$baseUrl .= $serverPath;

/**
 * Server path. XXX Without trailing slash.
 * 
 * @var string
 * @example /mongo
 */
define('MPG_SERVER_PATH', $serverPath);

/**
 * Base URL. XXX Without trailing slash.
 * 
 * @var string
 * @example //127.0.0.1:5000/mongo
 */
define('MPG_BASE_URL', $baseUrl);

require MPG_ABS_PATH . '/autoload.php';
require MPG_ABS_PATH . '/routes.php';

$application = new Application($router);
$serverRequest = ServerRequestFactory::createFromGlobals();

try {
    $response = $application->dispatch($serverRequest);
} catch (NotFoundHttpException $e) {
    die('Route not found. Try to append a slash to URL.');
}

$application->send($response);
