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

        if ( !empty(MPG_MONGODB_DATABASE) ) {
            $clientUri .= '/' . MPG_MONGODB_DATABASE;
        }

        return new MongoDBClient($clientUri);

    }

    /**
     * Gets all keys from a multidimensional array.
     * @see https://gist.github.com/JohnQUnknown/8761761
     * 
     * @param array $array
     * 
     * @return array
     */
    public static function arrayKeysMulti(array $array) : array {

        $keys = [];

        foreach ($array as $key => $_value) {
            $keys[] = $key;

            if ( is_array($array[$key]) ) {
                $keys = array_merge($keys, self::arrayKeysMulti($array[$key]));
            }
        }

        return $keys;

    }

}
