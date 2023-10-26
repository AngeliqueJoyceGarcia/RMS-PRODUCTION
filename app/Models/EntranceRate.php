<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\DefaultEntranceRate;

class EntranceRate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'rate_name',
        'vat',
        'servicecharge',
        'baseChildPrice',
        'baseAdultPrice',
        'baseSeniorPrice',
        'vatsc_childprice',
        'vatsc_adultprice',
        'vatsc_seniorprice',
    ];

    public function defaultRates()
    {
        return $this->hasMany(DefaultEntranceRate::class, 'weekday_rate_id', 'weekend_rate_id');
    }
}
