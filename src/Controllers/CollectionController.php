<?php

namespace Controllers;

use Capsule\Response;
use Helpers\MongoDBHelper;

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

        if ( isset($decodedRequestBody['filter']['_id'])
            && is_string($decodedRequestBody['filter']['_id']) ) {
                $decodedRequestBody['filter']['_id'] =
                    new \MongoDB\BSON\ObjectId($decodedRequestBody['filter']['_id']);
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

        if ( isset($decodedRequestBody['filter']['_id'])
            && is_string($decodedRequestBody['filter']['_id']) ) {
                $decodedRequestBody['filter']['_id'] =
                    new \MongoDB\BSON\ObjectId($decodedRequestBody['filter']['_id']);
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

        if ( isset($decodedRequestBody['filter']['_id'])
            && is_string($decodedRequestBody['filter']['_id']) ) {
                $decodedRequestBody['filter']['_id'] =
                    new \MongoDB\BSON\ObjectId($decodedRequestBody['filter']['_id']);
        }

        $documents = $collection->find(
            $decodedRequestBody['filter'], $decodedRequestBody['options']
        )->toArray();

        return new Response(
            200, json_encode($documents), ['Content-Type' => 'application/json']
        );

    }

    /**
     * @see https://docs.mongodb.com/php-library/v1.6/reference/method/MongoDBCollection-updateOne/index.html
     */
    public function updateOneAction($databaseName, $collectionName) : Response {

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

        if ( isset($decodedRequestBody['filter']['_id'])
            && is_string($decodedRequestBody['filter']['_id']) ) {
                $decodedRequestBody['filter']['_id'] =
                    new \MongoDB\BSON\ObjectId($decodedRequestBody['filter']['_id']);
        }

        $updateResult = $collection->updateOne(
            $decodedRequestBody['filter'], $decodedRequestBody['update']
        );

        return new Response(
            200,
            json_encode($updateResult->getModifiedCount()),
            ['Content-Type' => 'application/json']
        );

    }

    public function enumFieldsAction($databaseName, $collectionName) : Response {

        global $mongoDBClient;

        $collection = $mongoDBClient->$databaseName->$collectionName;

        $documents = $collection->find([], ['limit' => 1])->toArray();

        if ( empty($documents) ) {
            return new Response(404, 'Collection is empty');
        }

        $documentFields = MongoDBHelper::arrayKeysMulti(
            json_decode(json_encode($documents[0]), JSON_OBJECT_AS_ARRAY)
        );

        return new Response(
            200, json_encode($documentFields), ['Content-Type' => 'application/json']
        );

    }

}
