<?php

use Limber\Router\Router;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Capsule\Response;
use Controllers\IndexController;
use Controllers\DatabaseController;
use Controllers\CollectionController;

$router = new Router();

$router->get('/ping', function(ServerRequestInterface $request) : ResponseInterface {
	return new Response(200, 'pong');
});

$router->get('/', IndexController::class . '@indexAction');

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
