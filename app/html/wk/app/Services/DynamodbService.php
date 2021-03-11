<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Aws\Sdk;
use Aws\DynamoDb\Marshaler;
use Aws\DynamoDb\Exception\DynamoDbException;
use Exception;

/**
 * Dynamodb Service
 */
class DynamodbService
{

    /**
     * Dynamodbから取得
     */
    public function getItem($table_name, $key)
    {
        $sdk = new Sdk([
            'endpoint' => config('mywork.aws.dynamodb_endpoint'),
            'region' => config('mywork.aws.default_region'),
            'version' => 'latest'
        ]);

        $dynamodb = $sdk->createDynamoDb();
        $marshaler = new Marshaler();
        $key = $marshaler->marshalJson('{"image_name": "' . $key . '"}');
        $params = [
            'TableName' => $table_name,
            'Key' => $key,
        ];

        try {
            $result = $dynamodb->getItem($params);

            if ($result->hasKey('Item')) {
                return json_decode($result->get('Item')['detected_result']['S'], true);
            } else {
                return ['errors' => "画像が削除されたか、または現在解析中です。"];
            }
        } catch (DynamoDbException $e) {
            Log::error($e->getMessage());
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
