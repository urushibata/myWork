<?php

namespace App\Http\Controllers;

use App\Facades\DynamodbService;
use App\Facades\AmazonSnsService;
use App\Http\Requests\AjaxRequest;
use App\Models\PdfSort;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\RekognitionResource;
use RuntimeException;
use SebastianBergmann\Environment\Runtime;

class PdfSortController extends Controller
{
    /**
     * ファイルアップロード
     */
    public function upload(AjaxRequest $request)
    {
        $request->validate([
            'uploadFile' => ['required', 'file', 'mimetypes:application/pdf', 'max:5000'],
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
        $request->validate([
            'id' => 'required'
        ]);

        $rekognition_resource = RekognitionResource::find($request->id);
        $detect_result = DynamodbService::getItem('image-detected', $rekognition_resource->resource_path);

        return response()->json(
            [
                'url' => config('mywork.img_url') . $rekognition_resource->resource_path,
                'detect_result' => $detect_result
            ]
        );
    }

    /**
     * Amazon SNSにソートキーをpublishしてPDFをソートします。
     *
     * @return void
     */
    public function sort(AjaxRequest $request): void
    {
        $request->validate([
            'id' => 'required',
            'sort_id' => 'required'
        ]);

        Log::debug('id:' . $request->id);
        Log::debug('sort id:' . $request->sort_id);

        $rekognition_resource = RekognitionResource::find($request->id);
        $arn = config('mywork.aws.sns_pdf_sort_topic');
        $is_match = preg_match('/.*Text\/(.+)-\d\.jpeg/', $rekognition_resource->resource_path, $matches);

        if ($is_match !== 1) {
            throw new \Exception('PDF');
        }

        $target_pdf = config('mywork.aws.s3_pdf_save_path') . '/' . $matches[1] . '.pdf';
        $pdf_sort = new PdfSort();
        $pdf_sort->fill([
            'rekognition_resource_id' => $request->id,
            'org_pdf_path' => $target_pdf
        ])->save();

        $message = json_encode([
            'original_name' => $rekognition_resource->resource_original_name,
            'pdf_key' => $target_pdf,
            'sort_id' => $request->sort_id
        ]);
        $subject = 'Request PDF Sort Sns';
        AmazonSnsService::publish($arn, $message, $subject);
    }

    /**
     * Amazon SNSから送信されるPDFソート通知受信
     *
     * @return void
     */
    public function pdfSortFinished(): void
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
            $sort_pdf = $info['dirname'] . '/' . $info['filename'] . '/' . $info['basename'];

            Log::debug('shell: ' . $shell);
            Log::debug('org pdf: ' . $pdf);
            Log::debug('sorted pdf: ' . $sort_pdf);

            $pdf_sort = PdfSort::where("org_pdf_path", $pdf)->first();

            if ($pdf_sort) {
                $pdf_sort->fill([
                    'sorted_pdf_path' => $sort_pdf,
                ])->save();
            } else {
                throw new RuntimeException();
            }
        }
    }

    /**
     * ソート結果を取得します。
     */
    public function getSortResult(AjaxRequest $ajaxRequest)
    {
        Log::debug('get sort result: ' . $ajaxRequest->id);

        $ajaxRequest->validate([
            'id' => 'required'
        ]);

        $pdfSort = PdfSort::where('rekognition_resource_id', $ajaxRequest->id)->first();

        if ($pdfSort && $pdfSort->sorted_pdf_path) {
            return response()->json([
                'pdf_url' => config('mywork.img_url') . $pdfSort->sorted_pdf_path
            ]);
        }
    }
}
