<?php

namespace MPG;

use Capsule\Response;

class JsonResponse extends Response {

    public function __construct(int $statusCode, $body, array $headers = []) {

        $headers = array_merge($headers, ['Content-Type' => 'application/json']);

        return parent::__construct($statusCode, json_encode($body), $headers);
        
    }

}
