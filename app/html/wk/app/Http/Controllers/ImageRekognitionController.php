<?php

namespace App\Http\Controllers;

use App\Http\Requests\AjaxRequest;
use App\Models\RekognitionResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
        $rekognition_type = strtolower($request->rekognitionType);

        Log::debug('upload request rekognition type:' . $request->rekognitionType);
        try {
            $save_data = array_map(function ($file) use ($rekognition_type) {
                $path = Storage::disk('s3')->putFile(config("mywork.aws.s3_image_save_path.${rekognition_type}"), $file);

                $rekognition_resource = new RekognitionResource();
                $rekognition_resource->fill([
                    'resource_original_name' => $file->getClientOriginalName(),
                    'resource_path' => $path,
                    'rekognition_type' => $rekognition_type,
                    'user_name' => \Auth::user()->name,
                ])->save();

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
            $rekognition_resource = RekognitionResource::where('user_name', \Auth::user()->name)->orderBy('created_at')->get();

            return response()->json(
                $rekognition_resource->map(
                    function ($r) {
                        return [
                            'id' => $r->id,
                            'name' => $r->resource_original_name,
                            'path' => config('mywork.img_url') . $r->resource_path,
                            'rekognition_type' => $r->rekognition_type,
                            'upload_time' => $r->created_at->format("Y/m/d H:i:s"),
                        ];
                    }
                )
            );
        } catch (\Exception $e) {
        }
    }
}
