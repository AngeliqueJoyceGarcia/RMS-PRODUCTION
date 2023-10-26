<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ExtraChargeItem;

class ExtraItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ExtraChargeItem::create([
            'item_name' => 'Slippers',
            'price' => 100,
        ]);
        ExtraChargeItem::create([
            'item_name' => 'Goggles',
            'price' => 50,
        ]);
        ExtraChargeItem::create([
            'item_name' => 'Towel',
            'price' => 1000,
        ]);
        ExtraChargeItem::create([
            'item_name' => 'Wristband',
            'price' => 200,
        ]);
        ExtraChargeItem::create([
            'item_name' => 'Swim Rings Big',
            'price' => 200,
        ]);
        ExtraChargeItem::create([
            'item_name' => 'Swim Rings Medium',
            'price' => 150,
        ]);
    }
}
