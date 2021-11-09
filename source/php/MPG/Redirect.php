<?php

namespace MPG;

class Redirect {

    /**
     * Redirects to a route.
     * 
     * @param string $route Route with leading slash.
     */
    public static function to(string $route) {

        header('Location: ' . MPG_BASE_URL . $route);
        exit;

    }

}
