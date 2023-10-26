<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use Faker\Factory as Faker;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    public function run()
    {
        // Create a Faker instance
        $faker = Faker::create();

        $numberOfRecords = 1;

        for ($i = 0; $i < $numberOfRecords; $i++) {
            // Example data for a single Booking record
            // Booking::create([
            //     'check_in' => $faker->dateTimeBetween('+1 day', '+5 days'),
            //     'check_out' => $faker->dateTimeBetween('+2 days', '+6 days'),
            //     'rate_name' => $faker->randomElement(['Standard Rate', 'Holliday Rate']),
            //     'child' => $faker->numberBetween(50, 300),
            //     'adult' => $faker->numberBetween(50, 300),
            //     'senior' => $faker->numberBetween(50, 300),
            //     'reservation_type' => 'walkin',
            //     'children_qty' => $faker->numberBetween(0, 5),
            //     'adult_qty' => $faker->numberBetween(1, 5),
            //     'senior_qty' => $faker->numberBetween(0, 3),
            //     'total_companion' => $faker->numberBetween(1, 15),
            //     'name' => $faker->name,
            //     'contact_num' => $faker->numberBetween(1000000000, 9999999999), // Random 10-digit number
            //     'address' => $faker->address,
            //     'email' => $faker->email,
            //     'item_names' => $faker->words(3, true),
            //     'item_qty' => $faker->numberBetween(1, 5),
            //     'item_price' => $faker->randomFloat(2, 10, 1000),
            //     'total_itemprice' => $faker->randomFloat(2, 10, 1000),
            //     'arrived_companion' => $faker->numberBetween(1, 15),
            //     'total_amount' => $faker->randomFloat(2, 100, 1000),
            //     'payment_mode' => $faker->randomElement(['cash', 'gcash', 'BDO', 'OTC/creditcard']),
            //     'reference_num' => $faker->unique()->randomNumber(6),
            //     'card_num' => $faker->creditCardNumber,
            //     'acc_name' => $faker->name,
            //     'total_amount_paid' => $faker->randomFloat(2, 100, 1000),
            //     'status' => $faker->randomElement(['confirm sched', 'resched sched', 'cancel sched']),
            // ]);

            Booking::create([
                'check_in' => now(),
                'check_out' => $faker->dateTimeBetween('+2 days', '+6 days'),
                'rate_name' => $faker->randomElement(['Standard Rate', 'Holliday Rate']),
                'child' => $faker->numberBetween(50, 300),
                'adult' => $faker->numberBetween(50, 300),
                'senior' => $faker->numberBetween(50, 300),
                'reservation_type' => 'walkin',
                'children_qty' => $faker->numberBetween(0, 5),
                'adult_qty' => $faker->numberBetween(1, 5),
                'senior_qty' => $faker->numberBetween(0, 3),
                'total_companion' => 100,
                'name' => $faker->name,
                'contact_num' => $faker->numberBetween(1000000000, 9999999999), // Random 10-digit number
                'address' => $faker->address,
                'email' => $faker->email,
                'item_names' => $faker->words(3, true),
                'item_qty' => $faker->numberBetween(1, 5),
                'item_price' => $faker->randomFloat(2, 10, 1000),
                'total_itemprice' => $faker->randomFloat(2, 10, 1000),
                'arrived_companion' => $faker->numberBetween(1, 15),
                'total_amount' => $faker->randomFloat(2, 100, 1000),
                'payment_mode' => $faker->randomElement(['cash', 'gcash', 'BDO', 'OTC/creditcard']),
                'reference_num' => $faker->unique()->randomNumber(6),
                'card_num' => $faker->creditCardNumber,
                'acc_name' => $faker->name,
                'total_amount_paid' => $faker->randomFloat(2, 100, 1000),
                'status' => $faker->randomElement(['confirm sched', 'resched sched', 'cancel sched']),
            ]);
        }
    }
}
