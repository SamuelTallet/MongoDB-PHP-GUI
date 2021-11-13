<?php

namespace MPG;

use Limber\Router\Router;

$router = new Router();

$router->get(Request::getPath() . '/', function() {

    AuthController::ensureUserIsLogged();
    Request::redirectTo('/queryDatabase');

});

$router->get(
    Request::getPath() . '/login',
    AuthController::class . '@login'
);

$router->post(
    Request::getPath() . '/login',
    AuthController::class . '@login'
);

$router->get(
    Request::getPath() . '/manageCollections',
    CollectionsController::class . '@manage'
);

$router->post(
    Request::getPath() . '/listCollections',
    CollectionsController::class . '@list'
);

$router->post(
    Request::getPath() . '/createCollection',
    CollectionsController::class . '@create'
);

$router->post(
    Request::getPath() . '/renameCollection',
    CollectionsController::class . '@rename'
);

$router->post(
    Request::getPath() . '/dropCollection',
    CollectionsController::class . '@drop'
);

$router->get(
    Request::getPath() . '/importDocuments',
    DocumentsController::class . '@import'
);

$router->post(
    Request::getPath() . '/importDocuments',
    DocumentsController::class . '@import'
);

$router->get(
    Request::getPath() . '/visualizeDatabase',
    DatabasesController::class . '@visualize'
);

$router->get(
    Request::getPath() . '/getDatabaseNetworkGraph',
    DatabasesController::class . '@getNetworkGraph'
);

$router->get(
    Request::getPath() . '/queryDatabase',
    DatabasesController::class . '@query'
);

$router->post(
    Request::getPath() . '/insertOneDocument',
    DocumentsController::class . '@insertOne'
);

$router->post(
    Request::getPath() . '/countDocuments',
    DocumentsController::class . '@count'
);

$router->post(
    Request::getPath() . '/deleteOneDocument',
    DocumentsController::class . '@deleteOne'
);

$router->post(
    Request::getPath() . '/convertSQLToMongoDBQuery',
    SQLController::class . '@convertToMongoDBQuery'
);

$router->post(
    Request::getPath() . '/findDocuments',
    DocumentsController::class . '@find'
);

$router->post(
    Request::getPath() . '/updateOneDocument',
    DocumentsController::class . '@updateOne'
);

$router->post(
    Request::getPath() . '/enumCollectionFields',
    CollectionsController::class . '@enumFields'
);

$router->get(
    Request::getPath() . '/manageIndexes',
    IndexesController::class . '@manage'
);

$router->post(
    Request::getPath() . '/createIndex',
    IndexesController::class . '@create'
);

$router->post(
    Request::getPath() . '/listIndexes',
    IndexesController::class . '@list'
);

$router->post(
    Request::getPath() . '/dropIndex',
    IndexesController::class . '@drop'
);

$router->get(
    Request::getPath() . '/manageUsers',
    UsersController::class . '@manage'
);

$router->post(
    Request::getPath() . '/createUser',
    UsersController::class . '@create'
);

$router->post(
    Request::getPath() . '/listUsers',
    UsersController::class . '@list'
);

$router->post(
    Request::getPath() . '/dropUser',
    UsersController::class . '@drop'
);

$router->get(
    Request::getPath() . '/logout',
    AuthController::class . '@logout'
);

return $router;
