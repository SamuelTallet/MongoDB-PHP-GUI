<?php

namespace Helpers;

use MongoDB\Client as MongoDBClient;

class MongoDBHelper {

    /**
     * Creates a MongoDB client.
     * 
     * @return MongoDB\Client
     */
    public static function createClient() : MongoDBClient {

        $clientUri = 'mongodb://';

        if ( !empty(MPG_MONGODB_USER) && !empty(MPG_MONGODB_PASSWORD) ) {
            $clientUri .= MPG_MONGODB_USER . ':' . MPG_MONGODB_PASSWORD . '@';
        }

        $clientUri .= MPG_MONGODB_HOST;

        if ( !empty(MPG_MONGODB_PORT) ) {
            $clientUri .= ':' . MPG_MONGODB_PORT;
        }

        return new MongoDBClient($clientUri);

    }

}
