<?php

use Limber\Router\Router;
use Controllers\LoginController;
use Controllers\DatabaseController;
use Controllers\CollectionController;
use Controllers\Controller;

$router = new Router();

$router->get('/', function() {

	LoginController::ensureUserIsLogged();

	Controller::redirectTo('/queryDatabase');

});

// XXX This hack makes index to work in sub-folder case.
$router->get(MPG_SERVER_PATH . '/index', function() {

	LoginController::ensureUserIsLogged();

	Controller::redirectTo('/queryDatabase');

});

$router->get(
	MPG_SERVER_PATH . '/login',
	LoginController::class . '@renderViewAction'
);

$router->post(
	MPG_SERVER_PATH . '/login',
	LoginController::class . '@renderViewAction'
);

$router->get(
	MPG_SERVER_PATH . '/createDatabase',
	DatabaseController::class . '@renderCreateViewAction'
);

$router->get(
	MPG_SERVER_PATH . '/queryDatabase',
	DatabaseController::class . '@renderQueryViewAction'
);

$router->post(
	MPG_SERVER_PATH . '/ajaxDatabaseListCollections',
	DatabaseController::class . '@listCollectionsAction'
);

$router->post(
	MPG_SERVER_PATH . '/ajaxDatabaseCreateCollection',
	DatabaseController::class . '@createCollectionAction'
);

$router->post(
	MPG_SERVER_PATH . '/ajaxCollectionInsertOne',
	CollectionController::class . '@insertOneAction'
);

$router->post(
	MPG_SERVER_PATH . '/ajaxCollectionCount',
	CollectionController::class . '@countAction'
);

$router->post(
	MPG_SERVER_PATH . '/ajaxCollectionDeleteOne',
	CollectionController::class . '@deleteOneAction'
);

$router->post(
	MPG_SERVER_PATH . '/ajaxCollectionFind',
	CollectionController::class . '@findAction'
);

$router->post(
	MPG_SERVER_PATH . '/ajaxCollectionUpdateOne',
	CollectionController::class . '@updateOneAction'
);

$router->post(
	MPG_SERVER_PATH . '/ajaxCollectionEnumFields',
	CollectionController::class . '@enumFieldsAction'
);

$router->get(
	MPG_SERVER_PATH . '/manageIndexes',
	CollectionController::class . '@renderIndexesViewAction'
);

$router->post(
	MPG_SERVER_PATH . '/ajaxCollectionCreateIndex',
	CollectionController::class . '@createIndexAction'
);

$router->post(
	MPG_SERVER_PATH . '/ajaxCollectionListIndexes',
	CollectionController::class . '@listIndexesAction'
);

$router->post(
	MPG_SERVER_PATH . '/ajaxCollectionDropIndex',
	CollectionController::class . '@dropIndexAction'
);

$router->get(
	MPG_SERVER_PATH . '/logout',
	LoginController::class . '@logoutAction'
);
