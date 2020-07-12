<?php

namespace Controllers;

use Capsule\Response;

class DatabaseController extends Controller {

    /**
     * @see https://docs.mongodb.com/php-library/v1.6/reference/method/MongoDBDatabase-listCollections/index.html
     */
    public function listCollectionsAction($databaseName) : Response {

        global $mongoDBClient;

        $database = $mongoDBClient->$databaseName;

        $collectionsNames = [];

        foreach ($database->listCollections() as $collectionInfo) {
            $collectionsNames[] = $collectionInfo['name'];
        }
        
        return new Response(
            200, json_encode($collectionsNames), ['Content-Type' => 'application/json']
        );

    }

    /**
     * @see https://docs.mongodb.com/php-library/v1.6/reference/method/MongoDBDatabase-createCollection/index.html
     */
    public function createCollectionAction($databaseName, $collectionName) : Response {

        global $mongoDBClient;

        $mongoDBClient->$databaseName->createCollection($collectionName);
        
        return new Response(
            200, json_encode(true), ['Content-Type' => 'application/json']
        );

    }

}
