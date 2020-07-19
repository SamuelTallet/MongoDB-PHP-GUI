<?php

if ( !file_exists($autoload_file = __DIR__ . '/vendor/autoload.php') ) {
    die('Install dependencies with `composer install` to run this script successfully.');
}

$loader = require_once $autoload_file;

$loader->add('Controllers', __DIR__ . '/src');
$loader->add('Helpers', __DIR__ . '/src');
$loader->add('Normalizers', __DIR__ . '/src');
$loader->add('Responses', __DIR__ . '/src');
