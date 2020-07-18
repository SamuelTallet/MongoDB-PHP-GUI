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

        if ( !empty(MPG_MONGODB_USER) && !empty(MPG_MONGODB_PASSWORD) ) {
            $clientUri .= MPG_MONGODB_USER . ':' . MPG_MONGODB_PASSWORD . '@';
        }

        $clientUri .= MPG_MONGODB_HOST;

        if ( !empty(MPG_MONGODB_PORT) ) {
            $clientUri .= ':' . MPG_MONGODB_PORT;
        }

        if ( !empty(MPG_MONGODB_DATABASE) ) {
            $clientUri .= '/' . MPG_MONGODB_DATABASE;
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
