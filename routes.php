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

$router->post(
	'/ajax/database/listCollections',
	DatabaseController::class . '@listCollectionsAction'
);

$router->post(
	'/ajax/database/createCollection',
	DatabaseController::class . '@createCollectionAction'
);

$router->post(
	'/ajax/collection/insertOne',
	CollectionController::class . '@insertOneAction'
);

$router->post(
	'/ajax/collection/count',
	CollectionController::class . '@countAction'
);

$router->post(
	'/ajax/collection/deleteOne',
	CollectionController::class . '@deleteOneAction'
);

$router->post(
	'/ajax/collection/find',
	CollectionController::class . '@findAction'
);

$router->post(
	'/ajax/collection/updateOne',
	CollectionController::class . '@updateOneAction'
);

$router->post(
	'/ajax/collection/enumFields',
	CollectionController::class . '@enumFieldsAction'
);

$router->get(
	'/manageIndexes',
	CollectionController::class . '@renderIndexesViewAction'
);

$router->post(
	'/ajax/collection/createIndex',
	CollectionController::class . '@createIndexAction'
);

$router->post(
	'/ajax/collection/listIndexes',
	CollectionController::class . '@listIndexesAction'
);

$router->post(
	'/ajax/collection/dropIndex',
	CollectionController::class . '@dropIndexAction'
);
