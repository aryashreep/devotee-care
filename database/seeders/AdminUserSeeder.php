<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'Admin')->first();

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'mobile_number' => '9886543210',
            'password' => Hash::make('password'),
            'gender' => 'Male',
            'date_of_birth' => '1990-01-01',
            'marital_status' => 'Single',
            'address' => '123 Main St',
            'city' => 'Bangalore',
            'state' => 'Karnataka',
            'pincode' => '560001',
            'education_id' => 1,
            'profession_id' => 1,
            'blood_group_id' => 1,
            'initiated' => 0,
            'second_initiation' => 0,
            'bhakti_sadan_id' => 1,
            'life_membership' => 0,
        ]);

        $admin->roles()->attach($adminRole);
    }
}
