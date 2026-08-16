<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the users table with the admin account from the legacy repawcity.sql dump.
     */
    public function run(): void
    {
        User::create([
            'fname' => 'Aian Louise',
            'lname' => 'Alfaro',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('1234'),
            'user_type' => '1',
        ]);

        User::create([
            'fname' => 'Juan',
            'lname' => 'Dela Cruz',
            'email' => 'user@gmail.com',
            'password' => Hash::make('1234'),
            'user_type' => '2',
        ]);
    }
}
