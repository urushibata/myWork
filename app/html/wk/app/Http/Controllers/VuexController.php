<?php

namespace App\Http\Controllers;

use App\Http\Requests\AjaxRequest;

/**
 * Vuex取得用コントローラー
 */
class VuexController extends Controller
{
    /**
     * 初期値取得
     */
    public function init(AjaxRequest $request) {
        return response()->json([
            'user_name' => \Auth::user()->name,
            'img_url' => config('mywork.img_url'),
        ]);

    }
}
