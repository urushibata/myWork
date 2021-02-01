<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * ImageRecognitionController
 */
class ImageRecognitionController extends Controller
{

    /**
     * ファイルアップロード
     */
    public function upload(Request $request)
    {
        Log::debug($request);
        $file = $request->file("file");

        try {
            $path = Storage::disk('s3')->putFile(env("AWS_S3_IMAGE_SAVE_PATH"), $file);
            Log::debug($path);

            return $path;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function postDetectedResult()
    {

    }
}
