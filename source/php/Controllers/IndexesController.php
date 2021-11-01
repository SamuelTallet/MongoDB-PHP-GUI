<?php

namespace Controllers;

use Capsule\Response;
use Helpers\MongoDBHelper as MongoDB;
use Responses\JsonResponse;
use Normalizers\ErrorNormalizer;

class IndexesController extends Controller {

    public function manage() : Response {

        AuthController::ensureUserIsLogged();

        return new Response(200, $this->renderView('manageIndexes', [
            'databaseNames' => DatabasesController::getDatabaseNames()
        ]));

    }

    /**
     * @see https://docs.mongodb.com/php-library/v1.6/reference/method/MongoDBCollection-createIndex/index.html
     */
    public function create() : JsonResponse {

        try {
            $decodedRequestBody = $this->getDecodedRequestBody();
        } catch (\Throwable $th) {
            return new JsonResponse(400, ErrorNormalizer::normalize($th, __METHOD__));
        }

        try {

            $collection = MongoDB::getClient()->selectCollection(
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
    public function list() : JsonResponse {

        try {
            $decodedRequestBody = $this->getDecodedRequestBody();
        } catch (\Throwable $th) {
            return new JsonResponse(400, ErrorNormalizer::normalize($th, __METHOD__));
        }

        $indexes = [];

        try {

            $collection = MongoDB::getClient()->selectCollection(
                $decodedRequestBody['databaseName'], $decodedRequestBody['collectionName']
            );

            foreach ($collection->listIndexes() as $indexInfo) {
                $indexes[] = [
                    'name' => $indexInfo->getName(),
                    'keys' => $indexInfo->getKey(),
                    'isUnique' => $indexInfo->isUnique()
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
    public function drop() : JsonResponse {

        try {
            $decodedRequestBody = $this->getDecodedRequestBody();
        } catch (\Throwable $th) {
            return new JsonResponse(400, ErrorNormalizer::normalize($th, __METHOD__));
        }

        try {

            $collection = MongoDB::getClient()->selectCollection(
                $decodedRequestBody['databaseName'], $decodedRequestBody['collectionName']
            );

            // TODO: Check dropIndex result?
            $collection->dropIndex($decodedRequestBody['indexName']);

        } catch (\Throwable $th) {
            return new JsonResponse(500, ErrorNormalizer::normalize($th, __METHOD__));
        }

        return new JsonResponse(200, true);

    }

}