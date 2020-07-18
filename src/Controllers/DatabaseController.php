<?php

namespace Controllers;

use Capsule\Response;
use Helpers\MongoDBHelper;
use Helpers\ErrorNormalizer;
use Normalizer;

class DatabaseController extends Controller {

    public static function getDatabaseNames() : array {

        $mongoDBClient = MongoDBHelper::getClient();

        $databaseNames = [];

        if ( !empty(MPG_MONGODB_DATABASE) ) {
            $databaseNames[] = MPG_MONGODB_DATABASE;
        } else {

            try {
                foreach ($mongoDBClient->listDatabases() as $databaseInfo) {
                    $databaseNames[] = $databaseInfo['name'];
                }
            } catch (\Throwable $th) {
                ErrorNormalizer::prettyPrint($th); exit;
            }

        }

        return $databaseNames;

    }

    public function renderCreateViewAction() : Response {

        return new Response(200, $this->renderView('database.create'));

    }

    public function renderQueryViewAction() : Response {
        
        return new Response(200, $this->renderView('database.query', [
            'databaseNames' => self::getDatabaseNames()
        ]));

    }

    /**
     * @see https://docs.mongodb.com/php-library/v1.6/reference/method/MongoDBDatabase-listCollections/index.html
     */
    public function listCollectionsAction($databaseName) : Response {

        $mongoDBClient = MongoDBHelper::getClient();
        $database = $mongoDBClient->selectDatabase($databaseName);

        $collectionsNames = [];

        try {
            foreach ($database->listCollections() as $collectionInfo) {
                $collectionsNames[] = $collectionInfo['name'];
            }
        } catch (\Throwable $th) {
            return new Response(
                500,
                json_encode(ErrorNormalizer::normalize($th, __METHOD__)),
                ['Content-Type' => 'application/json']
            );
        }
        
        return new Response(
            200, json_encode($collectionsNames), ['Content-Type' => 'application/json']
        );

    }

    /**
     * @see https://docs.mongodb.com/php-library/v1.6/reference/method/MongoDBDatabase-createCollection/index.html
     */
    public function createCollectionAction($databaseName, $collectionName) : Response {

        $mongoDBClient = MongoDBHelper::getClient();
        $database = $mongoDBClient->selectDatabase($databaseName);

        $database->createCollection($collectionName);
        
        return new Response(
            200, json_encode(true), ['Content-Type' => 'application/json']
        );

    }

}
