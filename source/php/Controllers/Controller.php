<?php

namespace Controllers;

use Capsule\ServerRequest;
use Capsule\Stream\BufferStream;

class Controller {

    /**
     * Redirects to a route.
     * 
     * @param string $route
     */
    public static function redirectTo(string $route) {

        header('Location: ' . MPG_BASE_URL . $route); exit;

    }

    /**
     * If it exists: returns request body.
     * 
     * @return string|null
     */
    public function getRequestBody() : ?string {

        $requestBody = file_get_contents('php://input');

        return is_string($requestBody) ? $requestBody : null;
        
    }

    /**
     * Returns request body, decoded.
     * 
     * @throws \Exception
     * 
     * @return array
     */
    public function getDecodedRequestBody() : array {

        $requestBody = $this->getRequestBody();

        if ( is_null($requestBody) ) {
            throw new \Exception('Request body is missing.');
        }

        $decodedRequestBody = json_decode($requestBody, JSON_OBJECT_AS_ARRAY);

        if ( is_null($decodedRequestBody) ) {
            throw new \Exception('Request body is invalid.');
        }

        return $decodedRequestBody;

    }

    /**
     * Renders a view.
     * 
     * @param string $viewName View name.
     * @param array $viewData View data [optional].
     * 
     * @return string View result.
     */
    public function renderView(string $viewName, array $viewData = []) : string {

        extract($viewData);

        ob_start();

        require MPG_ABS_PATH . '/views/' . $viewName . '.view.php';

        $viewResult = (string) ob_get_contents();

        ob_end_clean();

        return $viewResult;

    }

}
