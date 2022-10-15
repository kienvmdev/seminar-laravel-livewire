<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class CategorySeeder extends Seeder
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
                'name' => 'Frontend',
                'parent_id' => null,
                'status' => 1,
            ],
            [
                'name' => 'Backend',
                'parent_id' => null,
                'status' => 1,
            ],
            [
                'name' => 'Database',
                'parent_id' => null,
                'status' => 1,
            ],
            [
                'name' => 'DevOps',
                'parent_id' => null,
                'status' => 1,
            ],
            [
                'name' => 'Git',
                'parent_id' => null,
                'status' => 1,
            ],
            [
                'name' => 'PHP',
                'parent_id' => 2,
                'status' => 1,
            ],
            [
                'name' => 'NodeJS',
                'parent_id' => 2,
                'status' => 1,
            ],
            [
                'name' => 'Python',
                'parent_id' => 2,
                'status' => 1,
            ],
            [
                'name' => 'Java',
                'parent_id' => 2,
                'status' => 1,
            ],
            [
                'name' => 'HTML-CSS',
                'parent_id' => 1,
                'status' => 1,
            ],
            [
                'name' => 'JavaScript',
                'parent_id' => 1,
                'status' => 1,
            ],
            [
                'name' => 'Vue',
                'parent_id' => 1,
                'status' => 1,
            ],
            [
                'name' => 'React',
                'parent_id' => 1,
                'status' => 1,
            ],
            [
                'name' => 'Angular',
                'parent_id' => 1,
                'status' => 1,
            ],
            [
                'name' => 'Java',
                'parent_id' => 1,
                'status' => 1,
            ],
            [
                'name' => 'Spring boot',
                'parent_id' => 1,
                'status' => 1,
            ],
            [
                'name' => 'Relation Database',
                'parent_id' => 1,
                'status' => 1,
            ],
            [
                'name' => 'NoSQL Database',
                'parent_id' => 1,
                'status' => 1,
            ],
            [
                'name' => 'NestJS',
                'parent_id' => 1,
                'status' => 1,
            ],
            [
                'name' => 'Typescript',
                'parent_id' => 1,
                'status' => 1,
            ],

        ];

        try {
            foreach ($datas as $data) {
                Category::create($data);
            }
        } catch (\Throwable $th) {
            Log::info($th);
        }
    }
}
