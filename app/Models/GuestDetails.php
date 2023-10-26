<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'name',
        'contact_num',
        'address',
        'email',
        'fbname',
        'bday',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'id');
    }

}
