<?php

use Limber\Router\Router;
use Controllers\AuthController;
use Controllers\Controller;
use Controllers\CollectionsController;
use Controllers\DocumentsController;
use Controllers\DatabasesController;
use Controllers\SQLController;
use Controllers\IndexesController;
use Controllers\UsersController;

$router = new Router();

$router->get(MPG_SERVER_PATH . '/', function() {

	AuthController::ensureUserIsLogged();
	Controller::redirectTo('/queryDatabase');

});

$router->get(
	MPG_SERVER_PATH . '/login',
	AuthController::class . '@login'
);

$router->post(
	MPG_SERVER_PATH . '/login',
	AuthController::class . '@login'
);

$router->get(
	MPG_SERVER_PATH . '/manageCollections',
	CollectionsController::class . '@manage'
);

$router->post(
	MPG_SERVER_PATH . '/listCollections',
	CollectionsController::class . '@list'
);

$router->post(
	MPG_SERVER_PATH . '/createCollection',
	CollectionsController::class . '@create'
);

$router->post(
	MPG_SERVER_PATH . '/renameCollection',
	CollectionsController::class . '@rename'
);

$router->post(
	MPG_SERVER_PATH . '/dropCollection',
	CollectionsController::class . '@drop'
);

$router->get(
	MPG_SERVER_PATH . '/importDocuments',
	DocumentsController::class . '@import'
);

$router->post(
	MPG_SERVER_PATH . '/importDocuments',
	DocumentsController::class . '@import'
);

$router->get(
	MPG_SERVER_PATH . '/visualizeDatabase',
	DatabasesController::class . '@visualize'
);

$router->get(
	MPG_SERVER_PATH . '/getDatabaseNetworkGraph',
	DatabasesController::class . '@getNetworkGraph'
);

$router->get(
	MPG_SERVER_PATH . '/queryDatabase',
	DatabasesController::class . '@query'
);

$router->post(
	MPG_SERVER_PATH . '/insertOneDocument',
	DocumentsController::class . '@insertOne'
);

$router->post(
	MPG_SERVER_PATH . '/countDocuments',
	DocumentsController::class . '@count'
);

$router->post(
	MPG_SERVER_PATH . '/deleteOneDocument',
	DocumentsController::class . '@deleteOne'
);

$router->post(
	MPG_SERVER_PATH . '/convertSQLToMongoDBQuery',
	SQLController::class . '@convertToMongoDBQuery'
);

$router->post(
	MPG_SERVER_PATH . '/findDocuments',
	DocumentsController::class . '@find'
);

$router->post(
	MPG_SERVER_PATH . '/updateOneDocument',
	DocumentsController::class . '@updateOne'
);

$router->post(
	MPG_SERVER_PATH . '/enumCollectionFields',
	CollectionsController::class . '@enumFields'
);

$router->get(
	MPG_SERVER_PATH . '/manageIndexes',
	IndexesController::class . '@manage'
);

$router->post(
	MPG_SERVER_PATH . '/createIndex',
	IndexesController::class . '@create'
);

$router->post(
	MPG_SERVER_PATH . '/listIndexes',
	IndexesController::class . '@list'
);

$router->post(
	MPG_SERVER_PATH . '/dropIndex',
	IndexesController::class . '@drop'
);

$router->get(
	MPG_SERVER_PATH . '/manageUsers',
	UsersController::class . '@manage'
);

$router->post(
	MPG_SERVER_PATH . '/createUser',
	UsersController::class . '@create'
);

$router->post(
	MPG_SERVER_PATH . '/listUsers',
	UsersController::class . '@list'
);

$router->post(
	MPG_SERVER_PATH . '/dropUser',
	UsersController::class . '@drop'
);

$router->get(
	MPG_SERVER_PATH . '/logout',
	AuthController::class . '@logout'
);
