<?php

namespace Controllers;

use Capsule\Response;
use Responses\JsonResponse;
use Helpers\MongoDBHelper;
use Normalizers\ErrorNormalizer;

class CollectionController extends Controller {

    /**
     * @see https://docs.mongodb.com/php-library/v1.6/reference/method/MongoDBCollection-insertMany/
     */
    public function importFile($documentsFilename, $databaseName, $collectionName) : int {

        $documentsFileContents = @file_get_contents($documentsFilename);

        if ( $documentsFileContents === false ) {
            throw new \Exception('Impossible to read the import file.');
        }

        $documents = json_decode($documentsFileContents, JSON_OBJECT_AS_ARRAY);

        if ( is_null($documents) ) {
            throw new \Exception('Import file is invalid... Malformed JSON?');
        }

        foreach ($documents as &$document) {

            if ( isset($document['_id'])
                && preg_match(MongoDBHelper::MDB_OBJECT_ID_REGEX, $document['_id']) ) {
                    $document['_id'] = new \MongoDB\BSON\ObjectId($document['_id']);
            }

            array_walk_recursive($document, function(&$documentValue) {

                if ( preg_match(MongoDBHelper::ISO_DATE_TIME_REGEX, $documentValue) ) {
                    $documentValue = new \MongoDB\BSON\UTCDateTime(new \DateTime($documentValue));
                }
    
            });

        }

        $collection = MongoDBHelper::getClient()->selectCollection(
            $databaseName, $collectionName
        );

        $insertManyResult = $collection->insertMany($documents);

        return $insertManyResult->getInsertedCount();

    }

    public function renderImportViewAction() : Response {

        LoginController::ensureUserIsLogged();

        $successMessage = '';
        $errorMessage = '';

        if ( isset($_FILES['import']) && isset($_FILES['import']['tmp_name'])
            && isset($_POST['database_name']) && isset($_POST['collection_name']) ) {

                try {

                    $importedDocumentsCount = $this->importFile(
                        $_FILES['import']['tmp_name'],
                        $_POST['database_name'],
                        $_POST['collection_name']
                    );

                    $successMessage = $importedDocumentsCount . ' document(s) imported.';

                } catch (\Throwable $th) {
                    $errorMessage = $th->getMessage();
                }

        }

        return new Response(200, $this->renderView('collection.import', [
            'databaseNames' => DatabaseController::getDatabaseNames(),
            'maxFileSize' => ini_get('upload_max_filesize'),
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage
        ]));

    }

    public function renderIndexesViewAction() : Response {

        LoginController::ensureUserIsLogged();

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

        if ( isset($decodedRequestBody['document']['_id'])
            && preg_match(MongoDBHelper::MDB_OBJECT_ID_REGEX, $decodedRequestBody['document']['_id']) ) {
                $decodedRequestBody['document']['_id'] =
                    new \MongoDB\BSON\ObjectId($decodedRequestBody['document']['_id']);
        }

        array_walk_recursive($decodedRequestBody['document'], function(&$documentValue) {

            if ( preg_match(MongoDBHelper::ISO_DATE_TIME_REGEX, $documentValue) ) {
                $documentValue = new \MongoDB\BSON\UTCDateTime(new \DateTime($documentValue));
            }

        });

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
            && preg_match(MongoDBHelper::MDB_OBJECT_ID_REGEX, $decodedRequestBody['filter']['_id']) ) {
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
            && preg_match(MongoDBHelper::MDB_OBJECT_ID_REGEX, $decodedRequestBody['filter']['_id']) ) {
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
            && preg_match(MongoDBHelper::MDB_OBJECT_ID_REGEX, $decodedRequestBody['filter']['_id']) ) {
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

        foreach ($documents as &$document) {

            $document = $document->jsonSerialize();

            if ( property_exists($document, '_id')
                && is_a($document->_id, '\MongoDB\BSON\ObjectId') ) {
                    $document->_id = (string) $document->_id;
            }

            foreach ($document as &$documentValue) {

                if ( is_a($documentValue, '\MongoDB\Model\BSONDocument') ) {
    
                    $documentValue = $documentValue->jsonSerialize();
    
                    foreach ($documentValue as &$documentSubValue) {
    
                        if ( is_a($documentSubValue, '\MongoDB\BSON\UTCDateTime') ) {
                            $documentSubValue = $documentSubValue->toDateTime()->format('Y-m-d\TH:i:s.v\Z');
                        }
    
                        // TODO: Support more nested documents.
    
                    }
    
                } elseif ( is_a($documentValue, '\MongoDB\BSON\UTCDateTime') ) {
                    $documentValue = $documentValue->toDateTime()->format('Y-m-d\TH:i:s.v\Z');
                }
    
            }

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
            && preg_match(MongoDBHelper::MDB_OBJECT_ID_REGEX, $decodedRequestBody['filter']['_id']) ) {
                $decodedRequestBody['filter']['_id'] =
                    new \MongoDB\BSON\ObjectId($decodedRequestBody['filter']['_id']);
        }

        foreach ($decodedRequestBody['update']['$set'] as &$updateValue) {
            if ( preg_match(MongoDBHelper::ISO_DATE_TIME_REGEX, $updateValue) ) {
                $updateValue = new \MongoDB\BSON\UTCDateTime(new \DateTime($updateValue));
            }
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

        $document = $documents[0]->jsonSerialize();

        if ( property_exists($document, '_id')
            && is_a($document->_id, '\MongoDB\BSON\ObjectId') ) {
                $document->_id = (string) $document->_id;
        }

        foreach ($document as &$documentValue) {

            if ( is_a($documentValue, '\MongoDB\Model\BSONDocument') ) {

                $documentValue = $documentValue->jsonSerialize();

                foreach ($documentValue as &$documentSubValue) {

                    if ( is_a($documentSubValue, '\MongoDB\BSON\UTCDateTime') ) {
                        $documentSubValue = $documentSubValue->toDateTime()->format('Y-m-d\TH:i:s.v\Z');
                    }

                    // TODO: Support more nested documents.

                }

            } elseif ( is_a($documentValue, '\MongoDB\BSON\UTCDateTime') ) {
                $documentValue = $documentValue->toDateTime()->format('Y-m-d\TH:i:s.v\Z');
            }

        }

        $array = json_decode(json_encode($document), JSON_OBJECT_AS_ARRAY);

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

        return new JsonResponse(200, $documentFields);

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
