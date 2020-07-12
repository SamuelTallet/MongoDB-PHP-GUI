<?php

namespace Controllers;

use Capsule\Response;

class CollectionController extends Controller {

    /**
     * @see https://docs.mongodb.com/php-library/v1.6/reference/method/MongoDBCollection-insertOne/index.html
     */
    public function insertOneAction($databaseName, $collectionName) : Response {

        global $mongoDBClient;

        $collection = $mongoDBClient->$databaseName->$collectionName;

        $requestBody = $this->getRequestBody();

        if ( is_null($requestBody) ) {
            return new Response(400, 'Request body is missing.');
        }

        $decodedRequestBody = json_decode($requestBody, JSON_OBJECT_AS_ARRAY);

        if ( is_null($decodedRequestBody) ) {
            return new Response(400, 'Request body is invalid.');
        }

        $insertOneResult = $collection->insertOne($decodedRequestBody['document']);
        
        return new Response(
            200,
            json_encode($insertOneResult->getInsertedCount()),
            ['Content-Type' => 'application/json']
        );

    }

    /**
     * @see https://docs.mongodb.com/php-library/v1.6/reference/method/MongoDBCollection-countDocuments/
     */
    public function countAction($databaseName, $collectionName) : Response {

        global $mongoDBClient;

        $collection = $mongoDBClient->$databaseName->$collectionName;

        $requestBody = $this->getRequestBody();

        if ( is_null($requestBody) ) {
            return new Response(400, 'Request body is missing.');
        }

        $decodedRequestBody = json_decode($requestBody, JSON_OBJECT_AS_ARRAY);

        if ( is_null($decodedRequestBody) ) {
            return new Response(400, 'Request body is invalid.');
        }

        $count = $collection->countDocuments($decodedRequestBody['filter']);
        
        return new Response(
            200, json_encode($count), ['Content-Type' => 'application/json']
        );

    }

    /**
     * @see https://docs.mongodb.com/php-library/v1.6/reference/method/MongoDBCollection-deleteOne/index.html
     */
    public function deleteOneAction($databaseName, $collectionName) : Response {

        global $mongoDBClient;

        $collection = $mongoDBClient->$databaseName->$collectionName;

        $requestBody = $this->getRequestBody();

        if ( is_null($requestBody) ) {
            return new Response(400, 'Request body is missing.');
        }

        $decodedRequestBody = json_decode($requestBody, JSON_OBJECT_AS_ARRAY);

        if ( is_null($decodedRequestBody) ) {
            return new Response(400, 'Request body is invalid.');
        }

        $deleteOneResult = $collection->deleteOne($decodedRequestBody['filter']);
        
        return new Response(
            200,
            json_encode($deleteOneResult->getDeletedCount()),
            ['Content-Type' => 'application/json']
        );

    }

    /**
     * @see https://docs.mongodb.com/php-library/v1.6/reference/method/MongoDBCollection-find/index.html
     */
    public function findAction($databaseName, $collectionName) : Response {

        global $mongoDBClient;

        $collection = $mongoDBClient->$databaseName->$collectionName;

        $requestBody = $this->getRequestBody();

        if ( is_null($requestBody) ) {
            return new Response(400, 'Request body is missing.');
        }

        $decodedRequestBody = json_decode($requestBody, JSON_OBJECT_AS_ARRAY);

        if ( is_null($decodedRequestBody) ) {
            return new Response(400, 'Request body is invalid.');
        }

        $documents = $collection->find(
            $decodedRequestBody['filter'], $decodedRequestBody['options']
        );

        $responseBody = [];

        foreach ($documents as $document) {
            $responseBody[] = $document;
        }
        
        return new Response(
            200, json_encode($responseBody), ['Content-Type' => 'application/json']
        );

    }

}
