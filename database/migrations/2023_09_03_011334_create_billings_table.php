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
        Schema::create('billings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');// foreing key of bookings
            $table->string('name');
            $table->string('bday')->nullable();
            $table->string('check_in')->nullable();
            $table->string('check_out')->nullable();
            $table->string('reservation_type'); 
            $table->integer('children_qty');
            $table->integer('adult_qty');
            $table->integer('senior_qty')->nullable();

            $table->integer('total_companion');
            $table->integer('arrived_companion');

            $table->string('rate_name');

            $table->integer('baseChildPrice');
            $table->integer('baseAdultPrice');
            $table->integer('baseSeniorPrice');

            $table->integer('vat');
            $table->integer('service_charge');

            $table->integer('vatsc_childprice');
            $table->integer('vatsc_adultprice');
            $table->integer('vatsc_seniorprice');

            $table->integer('returnedTowelQty')->default(0);
            $table->integer('returnedWristBandQty')->default(0);

            $table->integer('claimableRefund')->default(0);
            $table->integer('refundablePrice');

            $table->integer('checkin_payment')->nullable();

            $table->integer('total_itemprice');
            $table->decimal('total_amount', 12, 3); // 10 total digits, 3 decimal places,

            $table->string('payment_mode');

            $table->string('commission')->nullable(); // for banks
            $table->string('approval_code')->nullable(); // for credit cards
            $table->string('reference_num')->nullable(); // for gcash
            $table->string('card_num')->nullable(); // for BDO/OTC
            $table->string('acc_name')->nullable(); // for <BDO>
            $table->binary('file_attach')->nullable(); // for <website>
            $table->string('confirm_number')->nullable(); // for <website>
            $table->string('password_admin')->nullable(); // for <complimentary>
            $table->string('gc_number')->nullable(); // for <Gift Check>
            $table->string('validity')->nullable(); // for <Gift Check>
            $table->string('worth')->nullable(); // for <Gift Check>
            $table->decimal('total_amount_paid', 12, 3); 
            $table->decimal('total_prebookAmount', 12, 3);
            $table->decimal('balance', 12, 3);
            $table->string('status'); // confirm sched, resched sched, cancel sched
            $table->string('remarks')->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billings');
    }
};
