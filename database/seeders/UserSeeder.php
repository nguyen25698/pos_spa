<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@nailspa.com'],
            [
                'name' => 'Admin User',
                'email' => 'admin@nailspa.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'staff@nailspa.com'],
            [
                'name' => 'Staff User',
                'email' => 'staff@nailspa.com',
                'password' => Hash::make('password'),
                'role' => 'staff',
            ]
        );
    }
}
