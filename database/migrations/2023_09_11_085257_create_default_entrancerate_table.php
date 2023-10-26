<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('default_entrancerate', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('weekday_rate_id');
            $table->unsignedBigInteger('weekend_rate_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('default_entrancerate');
    }
};
