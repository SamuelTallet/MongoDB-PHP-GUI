<?php

namespace Controllers;

use Helpers\MongoDBHelper;
use Normalizers\ErrorNormalizer;
use Capsule\Response;
use Responses\JsonResponse;

class DatabaseController extends Controller {

    public static function getDatabaseNames() : array {

        $databaseNames = [];

        if ( isset($_SESSION['mpg']['mongodb_database']) ) {
            $databaseNames[] = $_SESSION['mpg']['mongodb_database'];
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

    public function renderVisualizeViewAction() : Response {

        LoginController::ensureUserIsLogged();
        
        return new Response(200, $this->renderView('database.visualize'));

    }

    public function getNetworkGraphAction() : Response {

        $networkGraph = [
            'visData' => [
                'nodes' => [
                    [
                        'id' => 1,
                        'label' => 'MongoDB server',
                        'shape' => 'image',
                        'image' => MPG_BASE_URL . '/static/images/leaf-icon.svg',
                        'size' => 32
                    ]
                ],
                'edges' => []
            ],
            'mapping' => [

                1 => [
                    'databaseName' => null,
                    'collectionName' => null
                ]

            ]
        ];

        $nodeCounter = 1;

        try {

            foreach (MongoDBHelper::getClient()->listDatabases() as $databaseInfo) {

                $nodeCounter++;

                $databaseNode = [
                    'id' => $nodeCounter,
                    'label' => 'DB: ' . $databaseInfo['name'],
                    'shape' => 'image',
                    'image' => MPG_BASE_URL . '/static/images/database-icon.svg',
                    'size' => 24
                ];

                $database = MongoDBHelper::getClient()->selectDatabase(
                    $databaseInfo['name']
                );
    
                foreach ($database->listCollections() as $collectionInfo) {

                    $nodeCounter++;
                    
                    $collectionNode = [
                        'id' => $nodeCounter,
                        'label' => 'Coll: ' . $collectionInfo['name'],
                        'shape' => 'image',
                        'image' => MPG_BASE_URL . '/static/images/document-icon.svg',
                        'size' => 24
                    ];

                    array_push($networkGraph['visData']['nodes'], $collectionNode);

                    array_push($networkGraph['visData']['edges'], [
                        'from' => $databaseNode['id'],
                        'to' => $collectionNode['id']
                    ]);

                    $networkGraph['mapping'][ $collectionNode['id'] ] = [
                        'databaseName' => $databaseInfo['name'],
                        'collectionName' => $collectionInfo['name']
                    ];

                }
                
                array_push($networkGraph['visData']['nodes'], $databaseNode);

                array_push($networkGraph['visData']['edges'], [
                    'from' => 1, // MongoDB server
                    'to' => $databaseNode['id']
                ]);

                $networkGraph['mapping'][ $databaseNode['id'] ] = [
                    'databaseName' => $databaseInfo['name'],
                    'collectionName' => null
                ];

            }

        } catch (\Throwable $th) {
            return new JsonResponse(500, ErrorNormalizer::normalize($th, __METHOD__));
        }

        return new JsonResponse(200, $networkGraph);

    }

    public function renderQueryViewAction() : Response {

        LoginController::ensureUserIsLogged();
        
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

            // TODO: Check createCollection result?
            $database->createCollection($decodedRequestBody['collectionName']);

        } catch (\Throwable $th) {
            return new JsonResponse(500, ErrorNormalizer::normalize($th, __METHOD__));
        }
        
        return new JsonResponse(200, true);

    }

}
