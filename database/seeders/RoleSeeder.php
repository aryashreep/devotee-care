<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'Admin', 'guard_name' => 'web']);
        Role::create(['name' => 'Management', 'guard_name' => 'web']);
        Role::create(['name' => 'Finance', 'guard_name' => 'web']);
        Role::create(['name' => 'Bhakti Sadan Leader', 'guard_name' => 'web']);
        Role::create(['name' => 'Bhakti Sadan Leader Assistance', 'guard_name' => 'web']);
        Role::create(['name' => 'Devotee', 'guard_name' => 'web']);
    }
}
