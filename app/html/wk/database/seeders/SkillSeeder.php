<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SkillGroup;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // skill group id
        // 1: lang, 2: fw, 3: db, 4: test, 5: aws, 6: mg
        $lang = 1;
        $fw = 2;
        $db = 3;
        $test = 4;
        $aws = 5;
        $mg = 6;

        DB::table('skills')->insert([
            [
                'skill_group_id' => $lang,
                'name' => 'Java',
                'sort_key' => 10,
            ],
            [
                'skill_group_id' => $lang,
                'name' => 'C#',
                'sort_key' => 20,
            ],
            [
                'skill_group_id' => $lang,
                'name' => 'PHP',
                'sort_key' => 30,
            ],
            [
                'skill_group_id' => $lang,
                'name' => 'JavaScript',
                'sort_key' => 40,
            ],
            [
                'skill_group_id' => $lang,
                'name' => 'HTML',
                'sort_key' => 50,
            ],
            [
                'skill_group_id' => $lang,
                'name' => 'CSS',
                'sort_key' => 60,
            ],
            [
                'skill_group_id' => $lang,
                'name' => 'Python',
                'sort_key' => 70,
            ],
            [
                'skill_group_id' => $fw,
                'name' => 'Struts1.x',
                'sort_key' => 10,
            ],
            [
                'skill_group_id' => $fw,
                'name' => 'Sprint',
                'sort_key' => 20,
            ],
            [
                'skill_group_id' => $fw,
                'name' => 'CakePHP',
                'sort_key' => 30,
            ],
            [
                'skill_group_id' => $fw,
                'name' => 'Smarty',
                'sort_key' => 40,
            ],
            [
                'skill_group_id' => $fw,
                'name' => 'jQuery',
                'sort_key' => 50,
            ],
            [
                'skill_group_id' => $fw,
                'name' => 'Vue.js',
                'sort_key' => 60,
            ],
            [
                'skill_group_id' => $fw,
                'name' => 'Bootstrap',
                'sort_key' => 70,
            ],
            [
                'skill_group_id' => $db,
                'name' => 'Oracle',
                'sort_key' => 10,
            ],
            [
                'skill_group_id' => $db,
                'name' => 'SQL Server',
                'sort_key' => 20,
            ],
            [
                'skill_group_id' => $db,
                'name' => 'MySQL',
                'sort_key' => 30,
            ],
            [
                'skill_group_id' => $test,
                'name' => 'JUnit',
                'sort_key' => 10,
            ],
            [
                'skill_group_id' => $test,
                'name' => 'NUnit',
                'sort_key' => 20,
            ],
            [
                'skill_group_id' => $test,
                'name' => 'PHPUnit',
                'sort_key' => 30,
            ],
            [
                'skill_group_id' => $aws,
                'name' => 'EC2',
                'sort_key' => 10,
            ],
            [
                'skill_group_id' => $aws,
                'name' => 'IAM',
                'sort_key' => 20,
            ],
            [
                'skill_group_id' => $aws,
                'name' => 'VPC',
                'sort_key' => 30,
            ],
            [
                'skill_group_id' => $aws,
                'name' => 'RDB',
                'sort_key' => 40,
            ],
            [
                'skill_group_id' => $aws,
                'name' => 'Lambda',
                'sort_key' => 50,
            ],
            [
                'skill_group_id' => $aws,
                'name' => 'FSx for windows',
                'sort_key' => 60,
            ],
            [
                'skill_group_id' => $aws,
                'name' => 'Cloud watch',
                'sort_key' => 70,
            ],
            [
                'skill_group_id' => $aws,
                'name' => 'Codecommit',
                'sort_key' => 80,
            ],
            [
                'skill_group_id' => $aws,
                'name' => 'DMS',
                'sort_key' => 90,
            ],
            [
                'skill_group_id' => $aws,
                'name' => 'S3',
                'sort_key' => 100,
            ],
            [
                'skill_group_id' => $aws,
                'name' => 'Directory Service',
                'sort_key' => 110,
            ],
            [
                'skill_group_id' => $mg,
                'name' => 'Jira',
                'sort_key' => 10,
            ],
            [
                'skill_group_id' => $mg,
                'name' => 'Trello',
                'sort_key' => 20,
            ],
            [
                'skill_group_id' => $mg,
                'name' => 'Confluence',
                'sort_key' => 30,
            ],
        ]);
    }
}
