<?php

use Limber\Router\Router;
use Controllers\DatabaseController;
use Controllers\CollectionController;

$router = new Router();

$router->get('/', function() {
	header('Location: /queryDatabase');
	exit;
});

$router->get(
	'/createDatabase',
	DatabaseController::class . '@renderCreateViewAction'
);

$router->get(
	'/queryDatabase',
	DatabaseController::class . '@renderQueryViewAction'
);

$router->get(
	'/ajax/database/{databaseName}/listCollections',
	DatabaseController::class . '@listCollectionsAction'
);

$router->get(
	'/ajax/database/{databaseName}/createCollection/{collectionName}',
	DatabaseController::class . '@createCollectionAction'
);

$router->post(
	'/ajax/database/{databaseName}/collection/{collectionName}/insertOne',
	CollectionController::class . '@insertOneAction'
);

$router->post(
	'/ajax/database/{databaseName}/collection/{collectionName}/count',
	CollectionController::class . '@countAction'
);

$router->post(
	'/ajax/database/{databaseName}/collection/{collectionName}/deleteOne',
	CollectionController::class . '@deleteOneAction'
);

$router->post(
	'/ajax/database/{databaseName}/collection/{collectionName}/find',
	CollectionController::class . '@findAction'
);

$router->post(
	'/ajax/database/{databaseName}/collection/{collectionName}/updateOne',
	CollectionController::class . '@updateOneAction'
);

$router->get(
	'/ajax/database/{databaseName}/collection/{collectionName}/enumFields',
	CollectionController::class . '@enumFieldsAction'
);

$router->get(
	'/manageIndexes',
	CollectionController::class . '@renderIndexesViewAction'
);

$router->post(
	'/ajax/database/{databaseName}/collection/{collectionName}/createIndex',
	CollectionController::class . '@createIndexAction'
);

$router->get(
	'/ajax/database/{databaseName}/collection/{collectionName}/listIndexes',
	CollectionController::class . '@listIndexesAction'
);

$router->post(
	'/ajax/database/{databaseName}/collection/{collectionName}/dropIndex',
	CollectionController::class . '@dropIndexAction'
);
