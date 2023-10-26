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
        Schema::table('villas', function (Blueprint $table) {
            $table->unsignedBigInteger('status_id'); // New foreign key column
            $table->foreign('status_id')
                  ->references('id')
                  ->on('statuses')
                  ->onDelete('restrict'); // Adjust onDelete as needed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('villas', function (Blueprint $table) {
            $table->dropForeign(['status_id']);
            $table->dropColumn('status_id');
        });
    }
};
