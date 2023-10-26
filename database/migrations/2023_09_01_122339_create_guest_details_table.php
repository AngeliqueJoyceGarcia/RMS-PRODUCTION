<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuestDetailsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('guest_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');// foreingn key of bookings
            $table->string('name');
            $table->string('contact_num');
            $table->string('address');
            $table->string('email');
            $table->string('fbname')->nullable();
            $table->string('bday');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_details');
    }
};
