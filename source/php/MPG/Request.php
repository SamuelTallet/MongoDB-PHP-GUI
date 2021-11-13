<?php

namespace MPG;

class Request {

    /**
     * @var string
     */
    private static $path;

    public static function initialize() {
        self::setPath();
    }

    private static function setPath() {

        // If request matches a folder. For example: /mongo/
        if ( preg_match('#/$#', $_SERVER['REQUEST_URI']) ) {

            $path = $_SERVER['REQUEST_URI'];

        } else {

            $path = dirname($_SERVER['REQUEST_URI']);

            // Normalize directory separator in request path.
            if ( DIRECTORY_SEPARATOR !== '/' ) {
                $path = str_replace(DIRECTORY_SEPARATOR, '/', $path);
            }

        }

        self::$path = rtrim($path, '/');

    }

    /**
     * Returns request path, without trailing slash.
     * Example: /mongo
     */
    public static function getPath() : string {
        return self::$path;
    }

    /**
     * Redirects to a route.
     * 
     * @param string $route Route with leading slash.
     * Example: /queryDatabase
     */
    public static function redirectTo(string $route) {

        header('Location: ' . BASE_URL . $route);
        exit;

    }

}
