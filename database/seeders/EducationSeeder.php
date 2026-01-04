<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Education;

class EducationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $educations = [
            ['name' => 'Primary School'],
            ['name' => 'High School (10th)'],
            ['name' => 'Higher Secondary (12th)'],
            ['name' => 'Diploma'],
            ['name' => 'ITI (Industrial Training Institute)'],
            ['name' => 'Bachelor of Arts (B.A.)'],
            ['name' => 'Bachelor of Science (B.Sc.)'],
            ['name' => 'Bachelor of Commerce (B.Com.)'],
            ['name' => 'Bachelor of Engineering / Technology (B.E., B.Tech)'],
            ['name' => 'Bachelor of Medicine (MBBS)'],
            ['name' => 'Master\'s Degree (e.g., M.A., M.Sc., M.Com, M.Tech)'],
            ['name' => 'Ph.D. (Doctor of Philosophy)'],
            ['name' => 'Other'],
        ];

        foreach ($educations as $education) {
            Education::firstOrCreate($education);
        }
    }
}
