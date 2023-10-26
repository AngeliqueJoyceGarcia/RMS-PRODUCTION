<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GuestDetails;

class GuestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GuestDetails::create([
            'name' => 'Sample Data',
            'contact_num' => 1234567890,
            'address' => '123 Main St',
            'email' => 'sample@example.com',
        ]);

        GuestDetails::create([
            'name' => 'Sample Data 2',
            'contact_num' => 1234127890,
            'address' => '321 Main St',
            'email' => 'sample2@example.com',
        ]);

    }
}
