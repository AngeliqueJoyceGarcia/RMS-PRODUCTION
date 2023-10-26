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
        Schema::create('max_pax', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('maximum_customers')->default(0);
            $table->timestamps();
        });

        // Insert records for Walk-in and Pre-book
        DB::table('max_pax')->insert([
            ['name' => 'Waterpark Capacity', 'maximum_customers' => 0],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('max_pax');
    }
};
