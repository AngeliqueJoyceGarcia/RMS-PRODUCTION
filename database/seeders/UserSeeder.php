<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'firstname' => 'sample',
            'lastname' => 'user',
            'email' => 'user@gmail.com',
            'password' => bcrypt('password'),
            'plaintext' => 'password',
            'is_active' => true,
            'role_id' => 2, // regular user
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        User::create([
            'firstname' => 'trial',
            'lastname' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'plaintext' => 'password',
            'is_active' => true,
            'role_id' => 1, // admin
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
    }
}
