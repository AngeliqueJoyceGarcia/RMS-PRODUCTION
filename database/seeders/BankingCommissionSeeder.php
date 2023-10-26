<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BankCommission;

class BankingCommissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BankCommission::create([
            'bank_name' => 'BDO Credit Card',
            'bank_commission_percentage' => '3%'
        ]);

        BankCommission::create([
            'bank_name' => 'BDO Debit Card',
            'bank_commission_percentage' => '3%'
        ]);

        BankCommission::create([
            'bank_name' => 'BDO Amex',
            'bank_commission_percentage' => '3%'
        ]);

        BankCommission::create([
            'bank_name' => 'RCBC Credit Card',
            'bank_commission_percentage' => '3%'
        ]);

        BankCommission::create([
            'bank_name' => 'BPI Credit Card',
            'bank_commission_percentage' => '3%'
        ]);
    }
}
