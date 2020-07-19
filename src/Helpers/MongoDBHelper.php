<?php

namespace Helpers;

use MongoDB\Client;

class MongoDBHelper {
 
    /**
     * MongoDB client singleton instance.
     * 
     * @var MongoDB\Client|null
     */
    private static $client;

    /**
     * Creates a MongoDB client.
     * 
     * @return MongoDB\Client
     */
    private static function createClient() : Client {

        $clientUri = 'mongodb://';

        if ( isset($_SESSION['mpg']['mongodb_user'])
            && isset($_SESSION['mpg']['mongodb_password'])
        ) {
            $clientUri .= $_SESSION['mpg']['mongodb_user'] . ':';
            $clientUri .= $_SESSION['mpg']['mongodb_password'] . '@';
        }

        $clientUri .= $_SESSION['mpg']['mongodb_host'];
        $clientUri .= ':' . $_SESSION['mpg']['mongodb_port'];

        if ( isset($_SESSION['mpg']['mongodb_database']) ) {
            $clientUri .= '/' . $_SESSION['mpg']['mongodb_database'];
        }

        return new Client($clientUri);

    }

    /**
     * Gets MongoBD client singleton instance.
     * 
     * @return MongoDB\Client
     */
    public static function getClient() : Client {

        if ( is_null(self::$client) ) {
            self::$client = self::createClient();  
        }

        return self::$client;

    }

}
