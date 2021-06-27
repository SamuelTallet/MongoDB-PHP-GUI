<?php

use Limber\Router\Router;
use Controllers\LoginController;
use Controllers\Controller;
use Controllers\DatabaseController;
use Controllers\CollectionController;
use Controllers\SQLController;

$router = new Router();

$router->get(MPG_SERVER_PATH . '/', function() {

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
	MPG_SERVER_PATH . '/manageCollections',
	CollectionController::class . '@renderViewAction'
);

$router->post(
	MPG_SERVER_PATH . '/ajaxDatabaseCreateCollection',
	DatabaseController::class . '@createCollectionAction'
);

$router->post(
	MPG_SERVER_PATH . '/ajaxCollectionRename',
	CollectionController::class . '@renameAction'
);

$router->post(
	MPG_SERVER_PATH . '/ajaxCollectionDrop',
	CollectionController::class . '@dropAction'
);

$router->get(
	MPG_SERVER_PATH . '/importDocuments',
	CollectionController::class . '@renderImportViewAction'
);

$router->post(
	MPG_SERVER_PATH . '/importDocuments',
	CollectionController::class . '@renderImportViewAction'
);

$router->get(
	MPG_SERVER_PATH . '/visualizeDatabase',
	DatabaseController::class . '@renderVisualizeViewAction'
);

$router->get(
	MPG_SERVER_PATH . '/ajaxDatabaseGetNetworkGraph',
	DatabaseController::class . '@getNetworkGraphAction'
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
	MPG_SERVER_PATH . '/ajaxSQLConvertToMongoDBQuery',
	SQLController::class . '@convertToMongoDBQueryAction'
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
	MPG_SERVER_PATH . '/manageUsers',
	DatabaseController::class . '@renderUsersViewAction'
);

$router->post(
	MPG_SERVER_PATH . '/ajaxDatabaseCreateUser',
	DatabaseController::class . '@createUserAction'
);

$router->post(
	MPG_SERVER_PATH . '/ajaxDatabaseListUsers',
	DatabaseController::class . '@listUsersAction'
);

$router->post(
	MPG_SERVER_PATH . '/ajaxDatabaseDropUser',
	DatabaseController::class . '@dropUserAction'
);

$router->get(
	MPG_SERVER_PATH . '/logout',
	LoginController::class . '@logoutAction'
);
