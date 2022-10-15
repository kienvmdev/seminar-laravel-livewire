<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@cppsw.com',
                'password' => bcrypt('A12345679'),
            ],
            [
                'name' => 'Manager',
                'email' => 'manager@cppsw.com',
                'password' => bcrypt('M12345679'),
            ]
        ];
        try {
            foreach ($users as $user) {
                User::create($user);
            }
        } catch (\Throwable $th) {
            Log::info($th);
        }
    }
}
