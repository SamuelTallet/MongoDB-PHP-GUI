<?php

namespace Controllers;

use Helpers\MongoDBHelper as MongoDB;
use Normalizers\ErrorNormalizer;
use Capsule\Response;
use Responses\JsonResponse;

class DatabasesController extends Controller {

    public static function getDatabaseNames() : array {

        $databaseNames = [];

        if ( isset($_SESSION['mpg']['mongodb_database']) ) {
            $databaseNames[] = $_SESSION['mpg']['mongodb_database'];
        } else {

            try {
                foreach (MongoDB::getClient()->listDatabases() as $databaseInfo) {
                    $databaseNames[] = $databaseInfo['name'];
                }
            } catch (\Throwable $th) {
                ErrorNormalizer::prettyPrintAndDie($th);
            }

        }

        sort($databaseNames);

        return $databaseNames;

    }

    public function visualize() : Response {

        AuthController::ensureUserIsLogged();
        
        return new Response(200, $this->renderView('visualizeDatabase'));

    }

    public function getNetworkGraph() : JsonResponse {

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

                $database = MongoDB::getClient()->selectDatabase($databaseName);
    
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

    public function query() : Response {

        AuthController::ensureUserIsLogged();
        
        return new Response(200, $this->renderView('queryDatabase', [
            'databaseNames' => self::getDatabaseNames()
        ]));

    }

}
