<?php

namespace Helpers;

use MongoDB\Client;

class MongoDBHelper {

    /**
     * Regular expression for a MongoDB ObjectID.
     * 
     * @var string
     * @see https://stackoverflow.com/questions/20988446/regex-for-mongodb-objectid
     */
    public const MDB_OBJECT_ID_REGEX = '/^[a-f\d]{24}$/i';

    /**
     * Regular expression for an ISO date-time.
     * 
     * @var string
     * @see https://stackoverflow.com/questions/3143070/javascript-regex-iso-datetime
     */
    public const ISO_DATE_TIME_REGEX = '/\d{4}-[01]\d-[0-3]\dT[0-2]\d:[0-5]\d:[0-5]\d\.\d+([+-][0-2]\d:[0-5]\d|Z)/';

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

        if ( !isset($_SESSION['mpg']['user_is_logged']) ) {
            throw new \Exception('Session expired. Refresh browser.');
        }

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
