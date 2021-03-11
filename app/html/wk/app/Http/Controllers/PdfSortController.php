<?php

namespace App\Http\Controllers;

use App\Facades\DynamodbService;
use App\Facades\AmazonSnsService;
use App\Http\Requests\AjaxRequest;
use App\Models\PdfSort;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\RekognitionResource;

class PdfSortController extends Controller
{
    /**
     * ファイルアップロード
     */
    public function upload(AjaxRequest $request)
    {
        $request->validate([
            'uploadFile' => ['file', 'mimetypes:application/pdf', 'max:5000'],
        ]);

        $file = $request->file('uploadFile');

        try {
            $path = Storage::disk('s3')->putFile(config('mywork.aws.s3_pdf_save_path'), $file);

            $base_name = basename($path, '.pdf');
            $top_page_path = config('mywork.aws.s3_image_save_path.text') . "/${base_name}-1.jpeg";

            $rekognition_resource = new RekognitionResource();
            $save_data = [
                'resource_original_name' => $file->getClientOriginalName(),
                'resource_path' => $top_page_path,
                'rekognition_type' => "Text",
                'user_name' => preg_replace('/guest_/', 'temp_', \Auth::user()->name),
            ];
            $rekognition_resource->fill($save_data)->save();

            return  $rekognition_resource;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * 画像解析結果取得
     */
    public function getDetect(AjaxRequest $request)
    {
        $id = $request->id;

        Log::debug('getDetect id:' . $id);

        $rekognition_resource = RekognitionResource::find($id);
        $detect_result = DynamodbService::getItem('image-detected', $rekognition_resource->resource_path);

        Log::debug('dynamodb result:' . var_export($detect_result, true));

        return response()->json(
            [
                'url' => config('mywork.img_url') . $rekognition_resource->resource_path,
                'detect_result' => $detect_result
            ]
        );
    }

    /**
     * PDFソート
     */
    public function sort(AjaxRequest $request)
    {
        Log::debug('sort id:' . $request->id);

        $rekognition_resource = RekognitionResource::find($request->id);
        $arn = config('mywork.aws.sns_pdf_sort_topic');
        $is_match = preg_match('/.*Text\/(.+)-\d\.jpeg/', $rekognition_resource->resource_path, $matches);

        if ($is_match !== 1) {
            throw new \Exception('PDF');
        }

        $message = json_encode([
            'original_name' => $rekognition_resource->resource_original_name,
            'pdf_key' => config('mywork.aws.s3_pdf_save_path') . '/' . $matches[1] . '.pdf',
            'sort_id' => $request->sort_id
        ]);
        $subject = 'Request PDF Sort Sns';
        AmazonSnsService::publish($arn, $message, $subject);
    }

    /**
     * PDFソート通知受信
     */
    public function pdfSortFinished(AjaxRequest $request)
    {
        $subscription = AmazonSnsService::subscribe();

        Log::debug('SNS subscription:' . var_export($subscription, true));

        if ($subscription['type'] === 'Notification') {
            $message = $subscription['message'];

            $container_overrides = $message->detail->overrides->containerOverrides[0];
            $command = $container_overrides->command;

            $shell = $command[0];
            $pdf = $command[1];

            $info = pathinfo($pdf);
            $org_pdf = $info['dirname'] . '.' . $info['extension'];

            Log::debug('shell: ' . $shell);
            Log::debug('sort pdf: ' . $pdf);
            Log::debug('org pdf: ' . $org_pdf);

            $rekognition_resource = RekognitionResource::where("resource_path", $org_pdf)->first();

            $pdf_sort = new PdfSort();
            $pdf_sort->fill([
                'rekognition_resource_id' => $rekognition_resource ? $rekognition_resource->id : null,
                'sorted_pdf_path' => $pdf,
            ])->save();
        }
    }

    public function fetchSortResult()
    {

    }
}
