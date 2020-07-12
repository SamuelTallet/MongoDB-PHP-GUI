<?php

if ( !file_exists($autoload_file = __DIR__ . '/vendor/autoload.php') ) {
    die('Install dependencies with `composer install` to run this script successfully.');
}

$loader = require_once $autoload_file;
$loader->add('Helpers', __DIR__ . '/src');
$loader->add('Controllers', __DIR__ . '/src');
