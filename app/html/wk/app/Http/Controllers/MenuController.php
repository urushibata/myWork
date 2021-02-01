<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * MenuController
 */
class MenuController extends Controller
{
    public function getProfile(Request $request)
    {
        Log::debug('called getProfile');
        $data = [
            'links' => [
                [
                    'url' => '/imageRekognition',
                    'label' => '画像解析'
                ]
            ]
        ];

        return $data;
    }
}
