<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Villa;
use Illuminate\Database\Seeder;

class VillasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Villa::create([
            'villaname' => 'Villa A',
            'pricing' => 200.00,
            'capacity' => 4,
            'description' => 'Luxurious villa with stunning views.',
            'images' => json_encode([
                'storage/villa_images/viper.png',
                'storage/villa_images/viper.png'
            ]),
            'status_id' => 1,
        ]);

        Villa::create([
            'villaname' => 'Villa B',
            'pricing' => 150.00,
            'capacity' => 6,
            'description' => 'Spacious villa with private pool.',
            'images' => json_encode([
                'storage/villa_images/viper.png'
            ]),
            'status_id' => 2,
        ]);

    }
}
