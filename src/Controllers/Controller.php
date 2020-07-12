<?php

namespace Controllers;

use Capsule\ServerRequest;
use Capsule\Stream\BufferStream;

class Controller {

    /**
     * If it exists: returns the server request body.
     * 
     * @return null|string
     */
    public static function getRequestBody() : ?string {

        $requestBody = file_get_contents('php://input');

        return is_string($requestBody) ? $requestBody : null;
        
    }

    /**
     * Renders a view.
     * 
     * @param string $viewName View name.
     * @param array $viewData View data [optional].
     * 
     * @return string View result.
     */
    public function renderView($viewName, $viewData = []) : string {

        extract($viewData);

        ob_start();

        require MPG_VIEWS_PATH . '/' . $viewName . '.tpl.php';

        $viewResult = (string) ob_get_contents();

        ob_end_clean();

        return $viewResult;

    }

}
