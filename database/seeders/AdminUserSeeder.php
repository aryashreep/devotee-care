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
            'mobile_number' => '9886543210',
            'password' => Hash::make('password'),
        ]);

        $admin->roles()->attach($adminRole);
    }
}
