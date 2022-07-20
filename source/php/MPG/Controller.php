<?php

namespace MPG;

class Controller {

    /**
     * If it exists: returns request body.
     * 
     * @return string|null
     */
    private function getRequestBody() : ?string {

        $requestBody = file_get_contents('php://input');

        return is_string($requestBody) ? $requestBody : null;
        
    }

    /**
     * Returns request body, decoded.
     * @deprecated
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

}
