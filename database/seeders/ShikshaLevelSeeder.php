<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ShikshaLevel;

class ShikshaLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shikshaLevels = [
            ['name' => 'Yet to accept'],
            ['name' => 'Krishna Sadhaka'],
            ['name' => 'Krishna Sevaka'],
            ['name' => 'Krishna Upasaka'],
            ['name' => 'Shraddhavan'],
            ['name' => 'Sri Guru-pada-ashraya'],
            ['name' => 'Srila Prabhupada ashraya'],
            ['name' => 'Diksha'],
            ['name' => 'Brahminical initiation'],
        ];

        foreach ($shikshaLevels as $level) {
            ShikshaLevel::firstOrCreate($level);
        }
    }
}
