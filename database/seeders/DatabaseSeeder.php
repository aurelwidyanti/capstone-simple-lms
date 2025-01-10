<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CourseSeeder::class,
        ]);

        $this->command->info('Database seeded successfully.');

        $parameters = [
            '--personal' => true,
            '--name' => 'Simple LMS Personal Access Client',
        ];

        Artisan::call('passport:client', $parameters);

        $this->command->info('Personal access client created successfully.');
    }
}
