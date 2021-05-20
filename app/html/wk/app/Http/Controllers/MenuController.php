<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\SkillGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * MenuController
 */
class MenuController extends Controller
{
    /**
     * 初期表示
     */
    public function init(Request $request)
    {
        return view('app');
    }

    /**
     * プロファイル表示
     */
    public function getProfile(Request $request)
    {
        Log::debug('call getProfile.');

        $profile = Profile::find(1);
        $profile->self_introduction = $profile->self_introduction;
        $skill_group = SkillGroup::with(['skill' => function ($q) {
            $q->orderBy('sort_key');
        }])->orderBy('sort_key')->get();

        $data = [
            'profile' => $profile,
            'skill_group' => $skill_group,
        ];

        return $data;
    }
}
