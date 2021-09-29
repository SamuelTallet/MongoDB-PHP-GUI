<?php

if ( !file_exists($autoload_file = MPG_ABS_PATH . '/vendor/autoload.php') ) {
    die('Run `composer install` to complete ' . MPG_APP_NAME . ' installation.');
}

$loader = require_once $autoload_file;

$loader->add('Controllers', MPG_ABS_PATH . '/source');
$loader->add('Helpers', MPG_ABS_PATH . '/source');
$loader->add('Normalizers', MPG_ABS_PATH . '/source');
$loader->add('Responses', MPG_ABS_PATH . '/source');
