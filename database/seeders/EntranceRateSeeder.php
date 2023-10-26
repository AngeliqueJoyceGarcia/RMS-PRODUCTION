<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EntranceRate;

class EntranceRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EntranceRate::create([
            'rate_name' => 'Weekday Day Tour Rate',
            'vat' => 150,
            'servicecharge' => 200,
            'baseChildPrice' => 2400,
            'baseAdultPrice' => 2600,
            'baseSeniorPrice' => 3500,
            'vatsc_childprice' => 2750,
            'vatsc_adultprice' => 2950,
            'vatsc_seniorprice' => 3850,
        ]);
        EntranceRate::create([
            'rate_name' => 'Weekend Day Tour Rate',
            'vat' => 100,
            'servicecharge' => 100,
            'baseChildPrice' => 2300,
            'baseAdultPrice' => 2500,
            'baseSeniorPrice' => 3400,
            'vatsc_childprice' => 2500,
            'vatsc_adultprice' => 2700,
            'vatsc_seniorprice' => 3600,
        ]);
    }
}
