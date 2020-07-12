<?php

use Helpers\MongoDBHelper;
use Limber\Application;
use Capsule\Factory\ServerRequestFactory;

require __DIR__ . '/autoload.php';
require __DIR__ . '/config.php';
require __DIR__ . '/routes.php';

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
define('MPG_APP_VERSION', '0.9.0');

/**
 * Development mode?
 * 
 * @var string
 */
define('MPG_DEV_MODE', false);

/**
 * Absolute path to views folder. XXX Without trailing slash.
 * 
 * @var string
 */
define('MPG_VIEWS_PATH', __DIR__ . '/views');

try {

    global $mongoDBClient;
    $mongoDBClient = MongoDBHelper::createClient();

    $application = new Application($router);
    $serverRequest = ServerRequestFactory::createFromGlobals();
    $response = $application->dispatch($serverRequest);
    $application->send($response);

} catch (\Throwable $th) {

    if ( MPG_DEV_MODE === true ) {
        print_r($th);
    }

}
