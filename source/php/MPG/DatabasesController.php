<?php

namespace MPG;

class DatabasesController extends Controller {

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

        sort($databaseNames);

        return $databaseNames;

    }

    public function visualize() : ViewResponse {

        AuthController::ensureUserIsLogged();
        
        return new ViewResponse(200, 'visualizeDatabase');

    }

    public function getGraph() : JsonResponse {

        $networkGraph = [
            'visData' => [
                'nodes' => [
                    [
                        'id' => 1,
                        'label' => 'MongoDB server',
                        'shape' => 'image',
                        'image' => './assets/images/leaf-icon.svg',
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
                    'image' => './assets/images/database-icon.svg',
                    'size' => 24
                ];

                $database = MongoDBHelper::getClient()->selectDatabase($databaseName);
    
                foreach ($database->listCollections() as $collectionInfo) {

                    $nodeCounter++;
                    
                    $collectionNode = [
                        'id' => $nodeCounter,
                        'label' => 'Coll: ' . $collectionInfo['name'],
                        'shape' => 'image',
                        'image' => './assets/images/document-icon.svg',
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

}
