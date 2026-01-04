<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Profession;

class ProfessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $professions = [
            ['name' => 'Student'],
            ['name' => 'Homemaker'],
            ['name' => 'Retired'],
            ['name' => 'Unemployed'],
            ['name' => 'Business / Self-Employed'],
            ['name' => 'Corporate / Office Professional'],
            ['name' => 'IT & Technology Professional'],
            ['name' => 'Medical & Healthcare Professional (Doctor, Nurse, etc.)'],
            ['name' => 'Legal & Administrative Professional'],
            ['name' => 'Education Professional (Teacher, Professor, etc.)'],
            ['name' => 'Government & Public Sector'],
            ['name' => 'Skilled Trades (Electrician, Plumber, etc.)'],
            ['name' => 'Arts, Media & Creative Professional'],
            ['name' => 'Social Work (NGO, Volunteer, etc.)'],
            ['name' => 'Agriculture / Farming'],
            ['name' => 'Other'],
        ];

        foreach ($professions as $profession) {
            Profession::firstOrCreate($profession);
        }
    }
}
