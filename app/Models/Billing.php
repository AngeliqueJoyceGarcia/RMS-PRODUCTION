<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GuestDetails;

class Billing extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'name',
        'bday',
        
        'check_in',
        'check_out',
        'reservation_type',
        'children_qty',
        'adult_qty',
        'senior_qty',

        'total_companion',
        'arrived_companion',

        'rate_name',

        'baseChildPrice',
        'baseAdultPrice',
        'baseSeniorPrice',

        'vat',
        'service_charge',

        'vatsc_childprice',
        'vatsc_adultprice',
        'vatsc_seniorprice',

        'returnedTowelQty',
        'returnedWristBandQty',

        'claimableRefund',
        'refundablePrice',
        

        'total_itemprice',
        'total_amount',

        'payment_mode',

        'commission',
        'approval_code',
        'reference_num',
        'card_num',
        'acc_name',
        'file_attach',
        'confirm_number',
        'password_admin',
        'gc_number',
        'validity',
        'worth',
        'total_amount_paid',
        'total_prebookAmount',
        'balance',
        'checkin_payment',
        'status',
        'remarks',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

}
