<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('check_in')->nullable();
            $table->string('check_out')->nullable();

            $table->string('rate_name'); //for rates monitoring
            $table->string('baseChildPrice');
            $table->string('baseAdultPrice');
            $table->string('baseSeniorPrice');
            $table->string('vatsc_childprice');
            $table->string('vatsc_adultprice');
            $table->string('vatsc_seniorprice');

            $table->string('reservation_type'); // walkin/prebook
            $table->integer('children_qty');
            $table->integer('adult_qty');
            $table->integer('senior_qty')->nullable();
            $table->integer('total_companion'); // for guest monitoring

            $table->string('name');
            $table->string('contact_num');
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('fbname')->nullable();
            $table->string('bday')->nullable();

            $table->string('item_names')->nullable(); // compilation of itemname
            $table->integer('item_qty')->default(0);// compilation of qty
            $table->string('item_price')->default(0);; // compilation of price
            $table->integer('total_itemprice'); // extras table summation of item_qty * item price

            $table->integer('arrived_companion');// realtime update for guest monitoring
            
            $table->decimal('total_amount', 12, 3); // 10 total digits, 3 decimal places, includes total companion, fee rates, extra charge item
            
            $table->string('payment_mode'); // hard coded cash=1, gcash=2, BDO=3, OTC/creditcard=4
            $table->integer('refundablePrice');

            $table->integer('checkin_payment')->nullable();

            $table->string('commission')->nullable(); // for banks
            $table->string('approval_code')->nullable(); // for credit
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
        Schema::dropIfExists('bookings');
    }
};
