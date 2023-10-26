<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EntranceRate;


class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'check_in',
        'check_out',
        'reservation_type',
        'rate_name',
        'baseChildPrice',
        'baseAdultPrice',
        'baseSeniorPrice',
        'vatsc_childprice',
        'vatsc_adultprice',
        'vatsc_seniorprice',
        'refundablePrice',
        'children_qty',
        'adult_qty',
        'senior_qty',
        'total_companion',
        'name',
        'contact_num',
        'address',
        'email',
        'fbname',
        'bday',
        'item_names',
        'item_qty',
        'item_price',
        'total_itemprice',
        'arrived_companion',
        'total_amount',
        'payment_mode',
        'approval_code',
        'commission',
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

    public function rate()
    {
        return $this->belongsTo(DayTourTimeSetting::class);
    }

    public function guestDetails()
    {
        return $this->hasOne(GuestDetails::class, 'id', 'booking_id');
    }

    public function billings()
    {
        return $this->hasMany(Billing::class);
    }
    
}
