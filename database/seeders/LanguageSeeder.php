<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Language;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            'Assamese', 'Bengali', 'Bodo', 'Dogri', 'Gujarati', 'Hindi',
            'Kannada', 'Kashmiri', 'Konkani', 'Maithili', 'Malayalam', 'Marathi',
            'Nepali', 'Odia', 'Punjabi', 'Sanskrit', 'Santali', 'Sindhi',
            'Tamil', 'Telugu', 'Urdu', 'English'
        ];

        foreach ($languages as $language) {
            Language::firstOrCreate(['name' => $language]);
        }
    }
}
