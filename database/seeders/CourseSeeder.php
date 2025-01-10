<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            [
                'name' => 'Data Structures and Algorithms',
                'description' => 'Learn data structures and algorithms from scratch',
                'price' => 300000,
                'teacher_id' => 1,
            ],
            [
                'name' => 'Introduction to Programming',
                'description' => 'Learn programming from scratch',
                'price' => 200000,
                'teacher_id' => 1,
            ],
            [
                'name' => 'Web Development',
                'description' => 'Learn web development from scratch',
                'price' => 400000,
                'teacher_id' => 1,
            ],
            [
                'name' => 'Mobile Development',
                'description' => 'Learn mobile development from scratch',
                'price' => 500000,
                'teacher_id' => 1,
            ],
            [
                'name' => 'Machine Learning',
                'description' => 'Learn machine learning from scratch',
                'price' => 600000,
                'teacher_id' => 1,
            ],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}
