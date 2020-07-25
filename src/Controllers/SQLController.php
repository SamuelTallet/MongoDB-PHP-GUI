<?php

namespace Controllers;

use Normalizers\ErrorNormalizer;
use Responses\JsonResponse;

class SQLController extends Controller {

    public function convertToMongoDBQueryAction() {

        try {
            $decodedRequestBody = $this->getDecodedRequestBody();
        } catch (\Throwable $th) {
            return new JsonResponse(400, ErrorNormalizer::normalize($th, __METHOD__));
        }

        $jarPath = '"' . MPG_ABS_PATH 
            . '/static/jar/sql-to-mongo-db-query-converter-1.13-standalone.jar"';
        $jarArgs = '--sql "' . str_replace('"', '\"', $decodedRequestBody['sql']) . '"';
        
        $command = 'java -jar ' . $jarPath . ' ' . $jarArgs;

        // If OS is Windows:
        if ( strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ) {
            // Force output.
            $command .= ' 2>&1';
        }

        $commandResult = str_replace("\n", '', shell_exec($command));
        
        $mongoDBQuery = [];

        try {
            if ( !preg_match_all('|^.*\.find\((.*)\)$|s', $commandResult, $mongoDBQuery) ) {
                throw new \Exception(
                    'Impossible to convert (SELECT) SQL query to MongoDB query... ' . 
                    'Try to install Java JDK on the computer hosting "' . MPG_APP_NAME . '".'
                );
            }
        } catch (\Throwable $th) {
            return new JsonResponse(500, ErrorNormalizer::normalize($th, __METHOD__));
        }

        return new JsonResponse(200, $mongoDBQuery[1][0]);

    }
    
}