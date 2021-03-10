<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkillGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('skill_groups')->insert([
            ['id' => 1, 'name' => '言語', 'sort_key' => 10],
            ['id' => 2, 'name' => 'フレームワーク、ライブラリ', 'sort_key' => 20],
            ['id' => 3, 'name' => 'DB', 'sort_key' => 30],
            ['id' => 4, 'name' => 'テスト', 'sort_key' => 40],
            ['id' => 5, 'name' => 'AWS', 'sort_key' => 50],
            ['id' => 6, 'name' => '管理系', 'sort_key' => 60],
        ]);
    }
}
