<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create some users
        User::factory()->count(10)->create(); // Create 10 random users

        // Create an admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'), // Use bcrypt for password hashing
        ]);
    }
}
