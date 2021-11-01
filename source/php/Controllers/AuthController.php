<?php

namespace Controllers;

use Helpers\MongoDBHelper as MongoDB;
use Capsule\Response;

class AuthController extends Controller {

    public static function ensureUserIsLogged() {

        if ( !isset($_SESSION['mpg']['user_is_logged']) ) {

            Controller::redirectTo('/login#');

        }

    }

    public function login() : Response {

        if ( isset($_POST['uri']) || isset($_POST['host']) ) {

            $requiredFields = $this->processFormData();
            
            if ( count($requiredFields) >= 1 ) {

                return new Response(200, $this->renderView('login', [
                    'requiredFields' => $requiredFields
                ]));
                
            } else {

                $_SESSION['mpg']['user_is_logged'] = true;
                Controller::redirectTo('/');

            }

        } else {
            return new Response(200, $this->renderView('login'));
        }

    }

    private function processFormData() : array {

        $requiredFields = [];
        $_SESSION['mpg'] = [];

        if ( isset($_POST['uri']) ) {

            if ( preg_match(MongoDB::URI_REGEX, $_POST['uri']) ) {
                $_SESSION['mpg']['mongodb_uri'] = $_POST['uri'];
            } else {
                $requiredFields[] = 'URI';
            }

        } elseif ( isset($_POST['host']) ) {

            if ( isset($_POST['user']) && !empty($_POST['user']) ) {
                $_SESSION['mpg']['mongodb_user'] = $_POST['user'];
            }
    
            if ( isset($_POST['password']) && !empty($_POST['password']) ) {
                $_SESSION['mpg']['mongodb_password'] = $_POST['password'];
            }
    
            if ( !empty($_POST['host']) ) {
                $_SESSION['mpg']['mongodb_host'] = $_POST['host'];
            } else {
                $requiredFields[] = 'Host';
            }
    
            if ( isset($_POST['port']) && !empty($_POST['port']) ) {
                $_SESSION['mpg']['mongodb_port'] = $_POST['port'];
            }
            
            if ( isset($_POST['database']) && !empty($_POST['database']) ) {
                $_SESSION['mpg']['mongodb_database'] = $_POST['database'];
            }

        } else {
            $requiredFields[] = 'URI or Host';
        }

        return $requiredFields;

    }

    public function logout() {

        $_SESSION['mpg'] = [];

        Controller::redirectTo('/login');

    }

}
