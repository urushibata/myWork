<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('profiles')->insert([
            [
                'name' => "漆畑 真也",
                'self_introduction' => "3PL企業で取引先企業と社内で使用するWMS、配送システムの開発運用を行っています。\n"
                    . "WebシステムはPHP、社内のシステムはC#を使用しています。\n"
                    . "AWSについては社内オンプレミスのファイルサーバー、ADサーバー、DBサーバーの移行と構築を行いました。",
                'avatar_alt' => 'urushibata avatar',
                'avatar_src' => 'https://coderdojo-mn.com/wp-content/uploads/2018/12/urusibata.jpg'
            ]
        ]);
    }
}
