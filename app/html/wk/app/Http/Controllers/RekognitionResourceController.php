<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\RekognitionResource;
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\Sdk;
use Aws\DynamoDb\Marshaler;

use Exception;

class RekognitionResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param RekognitionResource $rekognition_resource 表示対象のModel
     * @return \Illuminate\Http\Response
     */
    public function show(RekognitionResource $rekognition_resource)
    {
        Log::debug("show:" . $rekognition_resource);

        $sdk = new Sdk([
            'endpoint'   => env('DYNAMODB_ENDPOINT'),
            'region'   => env('AWS_DEFAULT_REGION'),
            'version'  => 'latest'
        ]);
        $dynamodb = $sdk->createDynamoDb();
        $marshaler = new Marshaler();
        $key = $marshaler->marshalJson('{"image_name": "' . $rekognition_resource->resource_path . '"}');
        $params = [
            'TableName' => 'image-detected',
            'Key' => $key,
        ];

        try {
            $result = $dynamodb->getItem($params);

            if ($result->hasKey('Item')) {
                return $result->get('Item')['detected_result']['S'];
            } else {
                return response()->json([
                    'errors' => "key not exsits."
                ], 422);
            }
        } catch (DynamoDbException $e) {
            Log::error($e->getMessage());
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param RekognitionResource $rekognition_resource 削除対象のModel
     * @return \Illuminate\Http\Response
     */
    public function destroy(RekognitionResource $rekognition_resource)
    {
        Log::debug('destroy method:' . $rekognition_resource);

        try {
            $rekognition_resource->delete();

            return [
                'id' => $rekognition_resource->id,
            ];
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
