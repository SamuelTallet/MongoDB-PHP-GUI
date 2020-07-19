<?php

namespace Controllers;

use Capsule\Response;
use Responses\JsonResponse;
use Helpers\MongoDBHelper;
use Normalizers\ErrorNormalizer;

class CollectionController extends Controller {

    public function renderIndexesViewAction() : Response {

        return new Response(200, $this->renderView('collection.indexes', [
            'databaseNames' => DatabaseController::getDatabaseNames()
        ]));

    }

    /**
     * @see https://docs.mongodb.com/php-library/v1.6/reference/method/MongoDBCollection-insertOne/index.html
     */
    public function insertOneAction() : Response {

        try {
            $decodedRequestBody = $this->getDecodedRequestBody();
        } catch (\Throwable $th) {
            return new JsonResponse(400, ErrorNormalizer::normalize($th, __METHOD__));
        }

        try {

            $collection = MongoDBHelper::getClient()->selectCollection(
                $decodedRequestBody['databaseName'], $decodedRequestBody['collectionName']
            );

            $insertOneResult = $collection->insertOne($decodedRequestBody['document']);

        } catch (\Throwable $th) {
            return new JsonResponse(500, ErrorNormalizer::normalize($th, __METHOD__));
        }

        return new JsonResponse(200, $insertOneResult->getInsertedCount());

    }

    /**
     * @see https://docs.mongodb.com/php-library/v1.6/reference/method/MongoDBCollection-countDocuments/
     */
    public function countAction() : Response {

        try {
            $decodedRequestBody = $this->getDecodedRequestBody();
        } catch (\Throwable $th) {
            return new JsonResponse(400, ErrorNormalizer::normalize($th, __METHOD__));
        }

        if ( isset($decodedRequestBody['filter']['_id'])
            && is_string($decodedRequestBody['filter']['_id']) ) {
                $decodedRequestBody['filter']['_id'] =
                    new \MongoDB\BSON\ObjectId($decodedRequestBody['filter']['_id']);
        }

        try {

            $collection = MongoDBHelper::getClient()->selectCollection(
                $decodedRequestBody['databaseName'], $decodedRequestBody['collectionName']
            );

            $count = $collection->countDocuments($decodedRequestBody['filter']);

        } catch (\Throwable $th) {
            return new JsonResponse(500, ErrorNormalizer::normalize($th, __METHOD__));
        }

        return new JsonResponse(200, $count);

    }

    /**
     * @see https://docs.mongodb.com/php-library/v1.6/reference/method/MongoDBCollection-deleteOne/index.html
     */
    public function deleteOneAction() : Response {

        try {
            $decodedRequestBody = $this->getDecodedRequestBody();
        } catch (\Throwable $th) {
            return new JsonResponse(400, ErrorNormalizer::normalize($th, __METHOD__));
        }

        if ( isset($decodedRequestBody['filter']['_id'])
            && is_string($decodedRequestBody['filter']['_id']) ) {
                $decodedRequestBody['filter']['_id'] =
                    new \MongoDB\BSON\ObjectId($decodedRequestBody['filter']['_id']);
        }

        try {

            $collection = MongoDBHelper::getClient()->selectCollection(
                $decodedRequestBody['databaseName'], $decodedRequestBody['collectionName']
            );

            $deleteOneResult = $collection->deleteOne($decodedRequestBody['filter']);

        } catch (\Throwable $th) {
            return new JsonResponse(500, ErrorNormalizer::normalize($th, __METHOD__));
        }

        return new JsonResponse(200, $deleteOneResult->getDeletedCount());

    }

    /**
     * @see https://docs.mongodb.com/php-library/v1.6/reference/method/MongoDBCollection-find/index.html
     */
    public function findAction() : Response {

        try {
            $decodedRequestBody = $this->getDecodedRequestBody();
        } catch (\Throwable $th) {
            return new JsonResponse(400, ErrorNormalizer::normalize($th, __METHOD__));
        }

        if ( isset($decodedRequestBody['filter']['_id'])
            && is_string($decodedRequestBody['filter']['_id']) ) {
                $decodedRequestBody['filter']['_id'] =
                    new \MongoDB\BSON\ObjectId($decodedRequestBody['filter']['_id']);
        }

        try {

            $collection = MongoDBHelper::getClient()->selectCollection(
                $decodedRequestBody['databaseName'], $decodedRequestBody['collectionName']
            );

            $documents = $collection->find(
                $decodedRequestBody['filter'], $decodedRequestBody['options']
            )->toArray();

        } catch (\Throwable $th) {
            return new JsonResponse(500, ErrorNormalizer::normalize($th, __METHOD__));
        }

        return new JsonResponse(200, $documents);

    }

    /**
     * @see https://docs.mongodb.com/php-library/v1.6/reference/method/MongoDBCollection-updateOne/index.html
     */
    public function updateOneAction() : Response {

        try {
            $decodedRequestBody = $this->getDecodedRequestBody();
        } catch (\Throwable $th) {
            return new JsonResponse(400, ErrorNormalizer::normalize($th, __METHOD__));
        }

        if ( isset($decodedRequestBody['filter']['_id'])
            && is_string($decodedRequestBody['filter']['_id']) ) {
                $decodedRequestBody['filter']['_id'] =
                    new \MongoDB\BSON\ObjectId($decodedRequestBody['filter']['_id']);
        }

        try {

            $collection = MongoDBHelper::getClient()->selectCollection(
                $decodedRequestBody['databaseName'], $decodedRequestBody['collectionName']
            );

            $updateResult = $collection->updateOne(
                $decodedRequestBody['filter'], $decodedRequestBody['update']
            );

        } catch (\Throwable $th) {
            return new JsonResponse(500, ErrorNormalizer::normalize($th, __METHOD__));
        }

        return new JsonResponse(200, $updateResult->getModifiedCount());

    }

    public function enumFieldsAction() : Response {

        try {
            $decodedRequestBody = $this->getDecodedRequestBody();
        } catch (\Throwable $th) {
            return new JsonResponse(400, ErrorNormalizer::normalize($th, __METHOD__));
        }

        try {

            $collection = MongoDBHelper::getClient()->selectCollection(
                $decodedRequestBody['databaseName'], $decodedRequestBody['collectionName']
            );

            $documents = $collection->find([], ['limit' => 1])->toArray();

        } catch (\Throwable $th) {
            return new JsonResponse(500, ErrorNormalizer::normalize($th, __METHOD__));
        }

        if ( empty($documents) ) {
            return new JsonResponse(200, []);
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

        return new JsonResponse(200, json_decode($fixedDocumentFields));

    }

    /**
     * @see https://docs.mongodb.com/php-library/v1.6/reference/method/MongoDBCollection-createIndex/index.html
     */
    public function createIndexAction() : Response {

        try {
            $decodedRequestBody = $this->getDecodedRequestBody();
        } catch (\Throwable $th) {
            return new JsonResponse(400, ErrorNormalizer::normalize($th, __METHOD__));
        }

        try {

            $collection = MongoDBHelper::getClient()->selectCollection(
                $decodedRequestBody['databaseName'], $decodedRequestBody['collectionName']
            );

            $createdIndexName = $collection->createIndex(
                $decodedRequestBody['key'], $decodedRequestBody['options']
            );

        } catch (\Throwable $th) {
            return new JsonResponse(500, ErrorNormalizer::normalize($th, __METHOD__));
        }

        return new JsonResponse(200, $createdIndexName);

    }

    /**
     * @see https://docs.mongodb.com/php-library/v1.6/reference/method/MongoDBCollection-listIndexes/index.html
     */
    public function listIndexesAction() : Response {

        try {
            $decodedRequestBody = $this->getDecodedRequestBody();
        } catch (\Throwable $th) {
            return new JsonResponse(400, ErrorNormalizer::normalize($th, __METHOD__));
        }

        $indexes = [];

        try {

            $collection = MongoDBHelper::getClient()->selectCollection(
                $decodedRequestBody['databaseName'], $decodedRequestBody['collectionName']
            );

            foreach ($collection->listIndexes() as $indexInfo) {
                $indexes[] = [
                    'keys' => $indexInfo->getKey(),
                    'name' => $indexInfo->getName()
                ];
            }

        } catch (\Throwable $th) {
            return new JsonResponse(500, ErrorNormalizer::normalize($th, __METHOD__));
        }

        return new JsonResponse(200, $indexes);

    }

    /**
     * @see https://docs.mongodb.com/php-library/v1.6/reference/method/MongoDBCollection-dropIndex/index.html
     */
    public function dropIndexAction() : Response {

        try {
            $decodedRequestBody = $this->getDecodedRequestBody();
        } catch (\Throwable $th) {
            return new JsonResponse(400, ErrorNormalizer::normalize($th, __METHOD__));
        }

        try {

            $collection = MongoDBHelper::getClient()->selectCollection(
                $decodedRequestBody['databaseName'], $decodedRequestBody['collectionName']
            );

            // TODO: Check dropIndex result.
            $collection->dropIndex($decodedRequestBody['indexName']);

        } catch (\Throwable $th) {
            return new JsonResponse(500, ErrorNormalizer::normalize($th, __METHOD__));
        }

        return new JsonResponse(200, true);

    }

}
