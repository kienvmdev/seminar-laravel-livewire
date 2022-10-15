<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas = [
            [
                'name' => 'Backend',
                'status' => 1,
            ],
            [
                'name' => 'Frontend',
                'status' => 1,
            ],
            [
                'name' => 'DevOps',
                'status' => 1,
            ],
            [
                'name' => 'Database',
                'status' => 1,
            ],
            [
                'name' => 'MySQL',
                'status' => 1,
            ],
            [
                'name' => 'MongoDB',
                'status' => 1,
            ],
            [
                'name' => 'MariaDB',
                'status' => 1,
            ],
            [
                'name' => 'Javascript',
                'status' => 1,
            ],
            [
                'name' => 'HTML',
                'status' => 1,
            ],
            [
                'name' => 'CSS',
                'status' => 1,
            ],
            [
                'name' => 'Python',
                'status' => 1,
            ],
            [
                'name' => 'Java',
                'status' => 1,
            ],
            [
                'name' => 'PHP',
                'status' => 1,
            ],
            [
                'name' => 'Jquery',
                'status' => 1,
            ],
            [
                'name' => 'Nodejs',
                'status' => 1,
            ],
            [
                'name' => 'Reactjs',
                'status' => 1,
            ],
            [
                'name' => 'Vuejs',
                'status' => 1,
            ],
            [
                'name' => 'Angular',
                'status' => 1,
            ],
            [
                'name' => 'Regex',
                'status' => 1,
            ],
            [
                'name' => 'Linux',
                'status' => 1,
            ],
            [
                'name' => 'Leet code',
                'status' => 1,
            ],
            [
                'name' => 'Hacker rank',
                'status' => 1,
            ],
            [
                'name' => 'Spring boot',
                'status' => 1,
            ],
            [
                'name' => 'Project',
                'status' => 1,
            ],
            [
                'name' => 'Machine Learning',
                'status' => 1,
            ],
            [
                'name' => 'Data science',
                'status' => 1,
            ],
            [
                'name' => 'Large scale',
                'status' => 1,
            ],
            [
                'name' => 'Laravel',
                'status' => 1,
            ],
            [
                'name' => 'Cron job',
                'status' => 1,
            ],
            [
                'name' => 'Git',
                'status' => 1,
            ],
            [
                'name' => 'Interview',
                'status' => 1,
            ],
            [
                'name' => 'Convention',
                'status' => 1,
            ],
            [
                'name' => 'Best practices',
                'status' => 1,
            ],
            [
                'name' => 'Tips tricks',
                'status' => 1,
            ],
            [
                'name' => 'Cheat sheet',
                'status' => 1,
            ],
            [
                'name' => 'Framework',
                'status' => 1,
            ],
            [
                'name' => 'Redux',
                'status' => 1,
            ],
            [
                'name' => 'Vuex',
                'status' => 1,
            ],
            [
                'name' => 'Firebase',
                'status' => 1,
            ],
            [
                'name' => 'Algorithm',
                'status' => 1,
            ],
            [
                'name' => 'Performance',
                'status' => 1,
            ],
            [
                'name' => 'GraphQL',
                'status' => 1,
            ],
            [
                'name' => 'AWS',
                'status' => 1,
            ],

        ];

        try {
            foreach ($datas as $data) {
                Tag::create($data);
            }
        } catch (\Throwable $th) {
            Log::info($th);
        }
    }
}
