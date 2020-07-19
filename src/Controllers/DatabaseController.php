<?php

namespace Controllers;

use Helpers\MongoDBHelper;
use Normalizers\ErrorNormalizer;
use Capsule\Response;
use Responses\JsonResponse;

class DatabaseController extends Controller {

    public static function getDatabaseNames() : array {

        $databaseNames = [];

        if ( !empty(MPG_MONGODB_DATABASE) ) {
            $databaseNames[] = MPG_MONGODB_DATABASE;
        } else {

            try {
                foreach (MongoDBHelper::getClient()->listDatabases() as $databaseInfo) {
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
    public function listCollectionsAction() : Response {

        try {
            $decodedRequestBody = $this->getDecodedRequestBody();
        } catch (\Throwable $th) {
            return new JsonResponse(400, ErrorNormalizer::normalize($th, __METHOD__));
        }

        try {

            $database = MongoDBHelper::getClient()->selectDatabase(
                $decodedRequestBody['databaseName']
            );

            $collectionNames = [];

            foreach ($database->listCollections() as $collectionInfo) {
                $collectionNames[] = $collectionInfo['name'];
            }

        } catch (\Throwable $th) {
            return new JsonResponse(500, ErrorNormalizer::normalize($th, __METHOD__));
        }

        return new JsonResponse(200, $collectionNames);

    }

    /**
     * @see https://docs.mongodb.com/php-library/v1.6/reference/method/MongoDBDatabase-createCollection/index.html
     */
    public function createCollectionAction() : Response {

        try {
            $decodedRequestBody = $this->getDecodedRequestBody();
        } catch (\Throwable $th) {
            return new JsonResponse(400, ErrorNormalizer::normalize($th, __METHOD__));
        }

        try {

            $database = MongoDBHelper::getClient()->selectDatabase(
                $decodedRequestBody['databaseName']
            );

            $database->createCollection($decodedRequestBody['collectionName']);

        } catch (\Throwable $th) {
            return new JsonResponse(500, ErrorNormalizer::normalize($th, __METHOD__));
        }
        
        return new JsonResponse(200, true);

    }

}
