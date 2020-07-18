<?php

namespace Controllers;

use Capsule\Response;
use Helpers\MongoDBHelper;
use Helpers\ErrorNormalizer;

class CollectionController extends Controller {

    public function renderIndexesViewAction() : Response {

        return new Response(200, $this->renderView('collection.indexes', [
            'databaseNames' => DatabaseController::getDatabaseNames()
        ]));

    }

    /**
     * @see https://docs.mongodb.com/php-library/v1.6/reference/method/MongoDBCollection-insertOne/index.html
     */
    public function insertOneAction($databaseName, $collectionName) : Response {

        $mongoDBClient = MongoDBHelper::getClient();
        $collection = $mongoDBClient->selectCollection($databaseName, $collectionName);

        $requestBody = $this->getRequestBody();

        if ( is_null($requestBody) ) {
            return new Response(400, 'Request body is missing.');
        }

        $decodedRequestBody = json_decode($requestBody, JSON_OBJECT_AS_ARRAY);

        if ( is_null($decodedRequestBody) ) {
            return new Response(400, 'Request body is invalid.');
        }

        try {
            $insertOneResult = $collection->insertOne($decodedRequestBody['document']);
        } catch (\Throwable $th) {
            return new Response(
                500,
                json_encode(ErrorNormalizer::normalize($th)),
                ['Content-Type' => 'application/json']
            );
        }

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

        $mongoDBClient = MongoDBHelper::getClient();
        $collection = $mongoDBClient->selectCollection($databaseName, $collectionName);

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

        try {
            $count = $collection->countDocuments($decodedRequestBody['filter']);
        } catch (\Throwable $th) {
            return new Response(
                500,
                json_encode(ErrorNormalizer::normalize($th)),
                ['Content-Type' => 'application/json']
            );
        }

        return new Response(
            200, json_encode($count), ['Content-Type' => 'application/json']
        );

    }

    /**
     * @see https://docs.mongodb.com/php-library/v1.6/reference/method/MongoDBCollection-deleteOne/index.html
     */
    public function deleteOneAction($databaseName, $collectionName) : Response {

        $mongoDBClient = MongoDBHelper::getClient();
        $collection = $mongoDBClient->selectCollection($databaseName, $collectionName);

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

        try {
            $deleteOneResult = $collection->deleteOne($decodedRequestBody['filter']);
        } catch (\Throwable $th) {
            return new Response(
                500,
                json_encode(ErrorNormalizer::normalize($th)),
                ['Content-Type' => 'application/json']
            );
        }
        
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

        $mongoDBClient = MongoDBHelper::getClient();
        $collection = $mongoDBClient->selectCollection($databaseName, $collectionName);

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

        try {
            $documents = $collection->find(
                $decodedRequestBody['filter'], $decodedRequestBody['options']
            )->toArray();
        } catch (\Throwable $th) {
            return new Response(
                500,
                json_encode(ErrorNormalizer::normalize($th)),
                ['Content-Type' => 'application/json']
            );
        }

        return new Response(
            200, json_encode($documents), ['Content-Type' => 'application/json']
        );

    }

    /**
     * @see https://docs.mongodb.com/php-library/v1.6/reference/method/MongoDBCollection-updateOne/index.html
     */
    public function updateOneAction($databaseName, $collectionName) : Response {

        $mongoDBClient = MongoDBHelper::getClient();
        $collection = $mongoDBClient->selectCollection($databaseName, $collectionName);

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

        try {
            $updateResult = $collection->updateOne(
                $decodedRequestBody['filter'], $decodedRequestBody['update']
            );
        } catch (\Throwable $th) {
            return new Response(
                500,
                json_encode(ErrorNormalizer::normalize($th)),
                ['Content-Type' => 'application/json']
            );
        }

        return new Response(
            200,
            json_encode($updateResult->getModifiedCount()),
            ['Content-Type' => 'application/json']
        );

    }

    public function enumFieldsAction($databaseName, $collectionName) : Response {

        $mongoDBClient = MongoDBHelper::getClient();
        $collection = $mongoDBClient->selectCollection($databaseName, $collectionName);

        try {
            $documents = $collection->find([], ['limit' => 1])->toArray();
        } catch (\Throwable $th) {
            return new Response(
                500,
                json_encode(ErrorNormalizer::normalize($th)),
                ['Content-Type' => 'application/json']
            );
        }

        if ( empty($documents) ) {
            return new Response(200, json_encode([]), ['Content-Type' => 'application/json']);
        }

        $array = json_decode(json_encode($documents[0]), JSON_OBJECT_AS_ARRAY);

        /**
         * Converts multidimensional array to 2D array with dot notation keys.
         * @see https://stackoverflow.com/questions/10424335/php-convert-multidimensional-array-to-2d-array-with-dot-notation-keys
         */
        $ritit = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($array));
        $result = [];

        foreach ($ritit as $unusedValue) {
            $keys = [];
            foreach (range(0, $ritit->getDepth()) as $depth) {
                $keys[] = $ritit->getSubIterator($depth)->key();
            }
            $result[] = join('.', $keys);
        }

        $documentFields = array_unique($result);

        // We ignore $oid since it represents a \MongoDB\BSON\ObjectId object.
        $fixedDocumentFields = str_replace('_id.$oid', '_id', json_encode($documentFields));

        return new Response(
            200, $fixedDocumentFields, ['Content-Type' => 'application/json']
        );

    }

    /**
     * @see https://docs.mongodb.com/php-library/v1.6/reference/method/MongoDBCollection-createIndex/index.html
     */
    public function createIndexAction($databaseName, $collectionName) : Response {

        $mongoDBClient = MongoDBHelper::getClient();
        $collection = $mongoDBClient->selectCollection($databaseName, $collectionName);

        $requestBody = $this->getRequestBody();

        if ( is_null($requestBody) ) {
            return new Response(400, 'Request body is missing.');
        }

        $decodedRequestBody = json_decode($requestBody, JSON_OBJECT_AS_ARRAY);

        if ( is_null($decodedRequestBody) ) {
            return new Response(400, 'Request body is invalid.');
        }

        try {
            $createdIndexName = $collection->createIndex(
                $decodedRequestBody['key'], $decodedRequestBody['options']
            );
        } catch (\Throwable $th) {
            return new Response(
                500,
                json_encode(ErrorNormalizer::normalize($th)),
                ['Content-Type' => 'application/json']
            );
        }

        return new Response(
            200, json_encode($createdIndexName), ['Content-Type' => 'application/json']
        );

    }

    /**
     * @see https://docs.mongodb.com/php-library/v1.6/reference/method/MongoDBCollection-listIndexes/index.html
     */
    public function listIndexesAction($databaseName, $collectionName) : Response {

        $mongoDBClient = MongoDBHelper::getClient();
        $collection = $mongoDBClient->selectCollection($databaseName, $collectionName);

        $indexes = [];

        try {
            foreach ($collection->listIndexes() as $indexInfo) {
                $indexes[] = [
                    'keys' => $indexInfo->getKey(),
                    'name' => $indexInfo->getName()
                ];
            }
        } catch (\Throwable $th) {
            return new Response(
                500,
                json_encode(ErrorNormalizer::normalize($th)),
                ['Content-Type' => 'application/json']
            );
        }

        return new Response(
            200, json_encode($indexes), ['Content-Type' => 'application/json']
        );

    }

    /**
     * @see https://docs.mongodb.com/php-library/v1.6/reference/method/MongoDBCollection-dropIndex/index.html
     */
    public function dropIndexAction($databaseName, $collectionName) : Response {

        $mongoDBClient = MongoDBHelper::getClient();
        $collection = $mongoDBClient->selectCollection($databaseName, $collectionName);

        $requestBody = $this->getRequestBody();

        if ( is_null($requestBody) ) {
            return new Response(400, 'Request body is missing.');
        }

        $decodedRequestBody = json_decode($requestBody, JSON_OBJECT_AS_ARRAY);

        if ( is_null($decodedRequestBody) ) {
            return new Response(400, 'Request body is invalid.');
        }

        try {
            $collection->dropIndex($decodedRequestBody['indexName']);
        } catch (\Throwable $th) {
            return new Response(
                500,
                json_encode(ErrorNormalizer::normalize($th)),
                ['Content-Type' => 'application/json']
            );
        }

        return new Response(
            200, json_encode(true), ['Content-Type' => 'application/json']
        );

    }

}
