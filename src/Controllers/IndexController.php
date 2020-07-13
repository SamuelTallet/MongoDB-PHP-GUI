<?php

namespace Controllers;

use Capsule\Response;

class IndexController extends Controller {

    public function indexAction() : Response {

        global $mongoDBClient;

        $databasesNames = [];

        if ( !empty(MPG_MONGODB_DATABASE) ) {
            $databasesNames[] = MPG_MONGODB_DATABASE;
        } else {
            foreach ($mongoDBClient->listDatabases() as $databaseInfo) {
                $databasesNames[] = $databaseInfo['name'];
            }
        }
        
        return new Response(200, $this->renderView('index', [
            'databasesNames' => $databasesNames
        ]));

    }

}
