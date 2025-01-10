<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'username' => 'admin',
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('password'),
                'handphone' => '081234567890',
                'description' => 'Administrator',
                'photo' => 'admin.jpg',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
