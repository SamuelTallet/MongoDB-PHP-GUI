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
                ErrorNormalizer::prettyPrintAndDie($th);
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

            foreach (self::getDatabaseNames() as $databaseName) {

                $nodeCounter++;

                $databaseNode = [
                    'id' => $nodeCounter,
                    'label' => 'DB: ' . $databaseName,
                    'shape' => 'image',
                    'image' => MPG_BASE_URL . '/static/images/database-icon.svg',
                    'size' => 24
                ];

                $database = MongoDBHelper::getClient()->selectDatabase($databaseName);
    
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
                        'databaseName' => $databaseName,
                        'collectionName' => $collectionInfo['name']
                    ];

                }
                
                array_push($networkGraph['visData']['nodes'], $databaseNode);

                array_push($networkGraph['visData']['edges'], [
                    'from' => 1, // MongoDB server
                    'to' => $databaseNode['id']
                ]);

                $networkGraph['mapping'][ $databaseNode['id'] ] = [
                    'databaseName' => $databaseName,
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

    public function renderUsersViewAction() : Response {

        LoginController::ensureUserIsLogged();
        
        return new Response(200, $this->renderView('database.users', [
            'databaseNames' => self::getDatabaseNames()
        ]));

    }

    /**
     * @see https://docs.mongodb.com/manual/reference/command/createUser/
     */
    public function createUserAction() : Response {

        try {
            $decodedRequestBody = $this->getDecodedRequestBody();
        } catch (\Throwable $th) {
            return new JsonResponse(400, ErrorNormalizer::normalize($th, __METHOD__));
        }

        try {

            $database = MongoDBHelper::getClient()->selectDatabase(
                $decodedRequestBody['databaseName']
            );

            // TODO: Check createUser result?
            $database->command([
                'createUser' => $decodedRequestBody['userName'],
                'pwd' => $decodedRequestBody['userPassword'],
                'roles' => $decodedRequestBody['userRoles']
            ]);

        } catch (\Throwable $th) {
            return new JsonResponse(500, ErrorNormalizer::normalize($th, __METHOD__));
        }

        return new JsonResponse(200, true);

    }

    /**
     * @see https://docs.mongodb.com/manual/reference/command/usersInfo/
     */
    public function listUsersAction() : Response {

        try {
            $decodedRequestBody = $this->getDecodedRequestBody();
        } catch (\Throwable $th) {
            return new JsonResponse(400, ErrorNormalizer::normalize($th, __METHOD__));
        }

        try {

            $database = MongoDBHelper::getClient()->selectDatabase(
                $decodedRequestBody['databaseName']
            );

            $usersInfoCommandResult = $database->command(['usersInfo' => 1]);
            $usersInfo = $usersInfoCommandResult->toArray()[0];

        } catch (\Throwable $th) {
            return new JsonResponse(500, ErrorNormalizer::normalize($th, __METHOD__));
        }

        return new JsonResponse(200, $usersInfo);

    }

    /**
     * @see https://docs.mongodb.com/manual/reference/command/dropUser/
     */
    public function dropUserAction() : Response {

        try {
            $decodedRequestBody = $this->getDecodedRequestBody();
        } catch (\Throwable $th) {
            return new JsonResponse(400, ErrorNormalizer::normalize($th, __METHOD__));
        }

        try {

            $database = MongoDBHelper::getClient()->selectDatabase(
                $decodedRequestBody['databaseName']
            );

            // TODO: Check dropUser result?
            $database->command(['dropUser' => $decodedRequestBody['userName']]);

        } catch (\Throwable $th) {
            return new JsonResponse(500, ErrorNormalizer::normalize($th, __METHOD__));
        }

        return new JsonResponse(200, true);

    }

}
