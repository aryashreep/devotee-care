<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(AdminUserSeeder::class);
        $this->call(LanguageSeeder::class);
        $this->call(BloodGroupSeeder::class);
        $this->call(LocationSeeder::class);
        $this->call(BhaktiSadanSeeder::class);
        $this->call(EducationSeeder::class);
        $this->call(ProfessionSeeder::class);
        $this->call(SevaSeeder::class);
        $this->call(ShikshaLevelSeeder::class);
    }
}
