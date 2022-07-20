<?php

namespace MPG;

class DocumentsController extends Controller {

    public static function formatRecursively(&$document) {

        foreach ($document as &$documentValue) {

            if ( is_a($documentValue, '\MongoDB\Model\BSONArray')
                || is_a($documentValue, '\MongoDB\Model\BSONDocument') ) {
    
                $documentValue = $documentValue->jsonSerialize();
                self::formatRecursively($documentValue);
    
            } elseif ( is_a($documentValue, '\MongoDB\BSON\UTCDateTime') ) {
                $documentValue = $documentValue->toDateTime()->format('Y-m-d\TH:i:s.v\Z');
            }

        }

    }

    public function import() : ViewResponse {

        AuthController::ensureUserIsLogged();

        $successMessage = '';
        $errorMessage = '';

        if ( isset($_FILES['import']) && isset($_FILES['import']['tmp_name'])
            && isset($_POST['database_name']) && isset($_POST['collection_name']) ) {

                try {

                    $importedDocumentsCount = self::importFromFile(
                        $_FILES['import']['tmp_name'],
                        $_POST['database_name'],
                        $_POST['collection_name']
                    );

                    $successMessage = $importedDocumentsCount . ' document(s) imported in '; 
                    $successMessage .= $_POST['collection_name'] . '.';

                } catch (\Throwable $th) {
                    $errorMessage = $th->getMessage();
                }

        }

        return new ViewResponse(200, 'importDocuments', [
            'databaseNames' => DatabasesController::getDatabaseNames(),
            'maxFileSize' => ini_get('upload_max_filesize'),
            'successMessage' => $successMessage,
            'errorMessage' => $errorMessage
        ]);

    }

    /**
     * @see https://docs.mongodb.com/php-library/v1.12/reference/method/MongoDBCollection-insertMany/
     */
    private static function importFromFile($documentsFilename, $databaseName, $collectionName) : int {

        $documentsFileContents = @file_get_contents($documentsFilename);

        if ( $documentsFileContents === false ) {
            throw new \Exception('Impossible to read the import file.');
        }

        // Remove UTF-8 BOM from uploaded file since UTF-8 BOM can disturb decoding of JSON.
        $documentsFileContents = preg_replace('/\x{FEFF}/u', '', $documentsFileContents, 1);

        $documents = json_decode($documentsFileContents, JSON_OBJECT_AS_ARRAY);

        if ( is_null($documents) ) {
            throw new \Exception('Import file is invalid... Malformed JSON?');
        }

        foreach ($documents as &$document) {

            if ( isset($document['_id']) && preg_match(MongoDBHelper::OBJECT_ID_REGEX, $document['_id']) ) {
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

    public function query() : ViewResponse {

        AuthController::ensureUserIsLogged();
        
        return new ViewResponse(200, 'queryDocuments', [
            'databaseNames' => DatabasesController::getDatabaseNames()
        ]);

    }

    /**
     * @see https://docs.mongodb.com/php-library/v1.12/reference/method/MongoDBCollection-insertOne/index.html
     */
    public function insertOne() : JsonResponse {

        try {
            $decodedRequestBody = $this->getDecodedRequestBody();
        } catch (\Throwable $th) {
            return new JsonResponse(400, ErrorNormalizer::normalize($th, __METHOD__));
        }

        if ( isset($decodedRequestBody['document']['_id'])
            && preg_match(MongoDBHelper::OBJECT_ID_REGEX, $decodedRequestBody['document']['_id']) ) {
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
     * @see https://docs.mongodb.com/php-library/v1.12/reference/method/MongoDBCollection-countDocuments/
     */
    public function count() : JsonResponse {

        try {
            $decodedRequestBody = $this->getDecodedRequestBody();
        } catch (\Throwable $th) {
            return new JsonResponse(400, ErrorNormalizer::normalize($th, __METHOD__));
        }

        if ( isset($decodedRequestBody['filter']['_id'])
            && preg_match(MongoDBHelper::OBJECT_ID_REGEX, $decodedRequestBody['filter']['_id']) ) {
                $decodedRequestBody['filter']['_id'] =
                    new \MongoDB\BSON\ObjectId($decodedRequestBody['filter']['_id']);
        }

        try {

            foreach ($decodedRequestBody['filter'] as &$filterValue) {
                if ( is_string($filterValue) && preg_match(MongoDBHelper::REGEX, $filterValue) ) {
                    $filterValue = MongoDBHelper::createRegexFromString($filterValue);
                }
            }

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
     * @see https://docs.mongodb.com/php-library/v1.12/reference/method/MongoDBCollection-deleteOne/index.html
     */
    public function deleteOne() : JsonResponse {

        try {
            $decodedRequestBody = $this->getDecodedRequestBody();
        } catch (\Throwable $th) {
            return new JsonResponse(400, ErrorNormalizer::normalize($th, __METHOD__));
        }

        if ( isset($decodedRequestBody['filter']['_id'])
            && preg_match(MongoDBHelper::OBJECT_ID_REGEX, $decodedRequestBody['filter']['_id']) ) {
                $decodedRequestBody['filter']['_id'] =
                    new \MongoDB\BSON\ObjectId($decodedRequestBody['filter']['_id']);
        }

        try {

            foreach ($decodedRequestBody['filter'] as &$filterValue) {
                if ( is_string($filterValue) && preg_match(MongoDBHelper::REGEX, $filterValue) ) {
                    $filterValue = MongoDBHelper::createRegexFromString($filterValue);
                }
            }

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
     * @see https://docs.mongodb.com/php-library/v1.12/reference/method/MongoDBCollection-find/index.html
     */
    public function find() : JsonResponse {

        try {
            $decodedRequestBody = $this->getDecodedRequestBody();
        } catch (\Throwable $th) {
            return new JsonResponse(400, ErrorNormalizer::normalize($th, __METHOD__));
        }

        if ( isset($decodedRequestBody['filter']['_id'])
            && preg_match(MongoDBHelper::OBJECT_ID_REGEX, $decodedRequestBody['filter']['_id']) ) {
                $decodedRequestBody['filter']['_id'] =
                    new \MongoDB\BSON\ObjectId($decodedRequestBody['filter']['_id']);
        }

        try {

            foreach ($decodedRequestBody['filter'] as &$filterValue) {
                if ( is_string($filterValue) && preg_match(MongoDBHelper::REGEX, $filterValue) ) {
                    $filterValue = MongoDBHelper::createRegexFromString($filterValue);
                }
            }

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

            if ( property_exists($document, '_id') && is_a($document->_id, '\MongoDB\BSON\ObjectId') ) {
                $document->_id = (string) $document->_id;
            }

            self::formatRecursively($document);

        }

        return new JsonResponse(200, $documents);

    }

    /**
     * @see https://docs.mongodb.com/php-library/v1.12/reference/method/MongoDBCollection-updateOne/index.html
     */
    public function updateOne() : JsonResponse {

        try {
            $decodedRequestBody = $this->getDecodedRequestBody();
        } catch (\Throwable $th) {
            return new JsonResponse(400, ErrorNormalizer::normalize($th, __METHOD__));
        }

        if ( isset($decodedRequestBody['filter']['_id']) ) {

            if ( preg_match(MongoDBHelper::OBJECT_ID_REGEX, $decodedRequestBody['filter']['_id']) ) {
                $decodedRequestBody['filter']['_id'] = new \MongoDB\BSON\ObjectId($decodedRequestBody['filter']['_id']);
            } elseif ( preg_match(MongoDBHelper::UINT_REGEX, $decodedRequestBody['filter']['_id']) ) {
                $decodedRequestBody['filter']['_id'] = intval($decodedRequestBody['filter']['_id']);
            }

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

}
