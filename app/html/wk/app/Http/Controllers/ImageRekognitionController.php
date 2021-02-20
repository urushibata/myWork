<?php

namespace App\Http\Controllers;

use App\Http\Requests\AjaxRequest;
use App\Models\RekognitionResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;
use Aws\Sdk;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * ImageRekognitionController
 */
class ImageRekognitionController extends Controller
{

    /**
     * ファイルアップロード
     */
    public function upload(AjaxRequest $request)
    {
        $request->validate([
            'uploadFiles' => 'required',
            'uploadFiles.*' => ['file', 'image', 'mimes:jpg,png', 'max:2000'],
            'rekognitionType' => 'required',
        ]);

        $files = $request->file('uploadFiles');
        $rekognition_type = strtoupper($request->rekognitionType);

        Log::debug('upload request rekognition type:' . $request->rekognitionType);
        try {
            $save_data = array_map(function ($file) use ($rekognition_type) {
                $path = Storage::disk('s3')->putFile(env("AWS_S3_IMAGE_SAVE_PATH_${rekognition_type}"), $file);

                $rekognition_resource = new RekognitionResource();
                $rekognition_resource->resource_original_name = $file->getClientOriginalName();
                $rekognition_resource->resource_path = $path;
                $rekognition_resource->rekognition_type = $rekognition_type;
                $rekognition_resource->user_id = 1;
                $rekognition_resource->save();

                return $rekognition_resource;
            }, $files);

            Log::debug(var_export($save_data, true));

            return $save_data;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * 画像解析完了通知受信
     */
    public function postDetectedFinished()
    {
    }

    /**
     * 画像解析結果リストを取得します。
     */
    public function getList()
    {
        try {
            $rekognition_resource = RekognitionResource::where('user_id', 1)->orderBy('created_at')->get();

            return response()->json(
                $rekognition_resource->map(
                    function ($r) {
                        return [
                            'id' => $r->id,
                            'name' => $r->resource_original_name,
                            'path' => env('IMG_URL') . $r->resource_path,
                            'rekognition_type' => $r->rekognition_type,
                            'upload_time' => $r->created_at->format("Y/m/d H:i:s"),
                        ];
                    }
                )
            );
        } catch (Exception $e) {
        }
    }

    /**
     * 解析結果表示
     *
     * @param RekognitionResource $rekognition_resource 表示対象のModel
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
     * RekognitionResourceを削除します。
     *
     * @param RekognitionResource $rekognition_resource 削除対象のModel
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
