<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomEvent extends Model
{
    use HasFactory;

    protected $table = 'custom_event';

    protected $fillable = [
        'entrance_rate_id',
        'event_date',
    ];

    public function entranceRate()
    {
        return $this->belongsTo(EntranceRate::class, 'entrance_rate_id');
    }
}
