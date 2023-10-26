<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('entrance_rates', function (Blueprint $table) {
            $table->id();
            $table->string('rate_name');
            $table->string('vat');
            $table->string('servicecharge');
            $table->string('baseChildPrice');
            $table->string('baseAdultPrice');
            $table->string('baseSeniorPrice');
            $table->string('vatsc_childprice');
            $table->string('vatsc_adultprice');
            $table->string('vatsc_seniorprice');
            $table->softDeletes();
            $table->timestamps();
        });

         // Insert entrance rate records
         DB::table('entrance_rates')->insert([
            [
                'rate_name' => 'Weekday Day Tour Rate',
                'vat' => 5,
                'servicecharge' => 10,
                'baseChildPrice' => 2400.00,
                'baseAdultPrice' => 2600.00,
                'baseSeniorPrice' => 2080.00,
                'vatsc_childprice' => 2760.00,
                'vatsc_adultprice' => 2990.00,
                'vatsc_seniorprice' => 2288.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'rate_name' => 'Weekend Day Tour Rate',
                'vat' => 2,
                'servicecharge' => 3,
                'baseChildPrice' => 2300.00,
                'baseAdultPrice' => 2500.00,
                'baseSeniorPrice' => 2000.00,
                'vatsc_childprice' => 2415.00,
                'vatsc_adultprice' => 2625.00,
                'vatsc_seniorprice' => 2100.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entrance_rates');
    }
};
