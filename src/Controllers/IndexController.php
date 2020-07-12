<?php

namespace Controllers;

use Capsule\Response;

class IndexController extends Controller {

    public function indexAction() : Response {

        global $mongoDBClient;

        $databasesNames = [];

        foreach ($mongoDBClient->listDatabases() as $databaseInfo) {
            $databasesNames[] = $databaseInfo['name'];
        }
        
        return new Response(200, $this->renderView('index', [
            'databasesNames' => $databasesNames
        ]));

    }

}
