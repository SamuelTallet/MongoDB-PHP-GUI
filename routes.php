<?php

namespace MPG;

use Limber\Router\Router;

Routes::setPrefix();

$router = new Router();

$router->get(Routes::getPrefix() . '/', function() {

    AuthController::ensureUserIsLogged();
    Routes::redirectTo('/queryDocuments');

});

$router->get(
    Routes::getPrefix() . '/login',
    AuthController::class . '@login'
);

$router->post(
    Routes::getPrefix() . '/login',
    AuthController::class . '@login'
);

$router->get(
    Routes::getPrefix() . '/manageCollections',
    CollectionsController::class . '@manage'
);

$router->post(
    Routes::getPrefix() . '/listCollections',
    CollectionsController::class . '@list'
);

$router->post(
    Routes::getPrefix() . '/createCollection',
    CollectionsController::class . '@create'
);

$router->post(
    Routes::getPrefix() . '/renameCollection',
    CollectionsController::class . '@rename'
);

$router->post(
    Routes::getPrefix() . '/dropCollection',
    CollectionsController::class . '@drop'
);

$router->get(
    Routes::getPrefix() . '/importDocuments',
    DocumentsController::class . '@import'
);

$router->post(
    Routes::getPrefix() . '/importDocuments',
    DocumentsController::class . '@import'
);

$router->get(
    Routes::getPrefix() . '/visualizeDatabase',
    DatabasesController::class . '@visualize'
);

$router->get(
    Routes::getPrefix() . '/getDatabaseGraph',
    DatabasesController::class . '@getGraph'
);

$router->get(
    Routes::getPrefix() . '/queryDocuments',
    DocumentsController::class . '@query'
);

$router->post(
    Routes::getPrefix() . '/insertOneDocument',
    DocumentsController::class . '@insertOne'
);

$router->post(
    Routes::getPrefix() . '/countDocuments',
    DocumentsController::class . '@count'
);

$router->post(
    Routes::getPrefix() . '/deleteOneDocument',
    DocumentsController::class . '@deleteOne'
);

$router->post(
    Routes::getPrefix() . '/convertSQLToMongoDBQuery',
    SQLController::class . '@convertToMongoDBQuery'
);

$router->post(
    Routes::getPrefix() . '/findDocuments',
    DocumentsController::class . '@find'
);

$router->post(
    Routes::getPrefix() . '/updateOneDocument',
    DocumentsController::class . '@updateOne'
);

$router->post(
    Routes::getPrefix() . '/enumCollectionFields',
    CollectionsController::class . '@enumFields'
);

$router->get(
    Routes::getPrefix() . '/manageIndexes',
    IndexesController::class . '@manage'
);

$router->post(
    Routes::getPrefix() . '/createIndex',
    IndexesController::class . '@create'
);

$router->post(
    Routes::getPrefix() . '/listIndexes',
    IndexesController::class . '@list'
);

$router->post(
    Routes::getPrefix() . '/dropIndex',
    IndexesController::class . '@drop'
);

$router->get(
    Routes::getPrefix() . '/manageUsers',
    UsersController::class . '@manage'
);

$router->post(
    Routes::getPrefix() . '/createUser',
    UsersController::class . '@create'
);

$router->post(
    Routes::getPrefix() . '/listUsers',
    UsersController::class . '@list'
);

$router->post(
    Routes::getPrefix() . '/dropUser',
    UsersController::class . '@drop'
);

$router->get(
    Routes::getPrefix() . '/logout',
    AuthController::class . '@logout'
);

return $router;
