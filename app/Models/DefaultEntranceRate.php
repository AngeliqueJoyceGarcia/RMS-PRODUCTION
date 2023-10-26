<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EntranceRate;

class DefaultEntranceRate extends Model
{
    use HasFactory;

    protected $table = 'default_entrancerate';

    protected $fillable = ['weekday_rate_id', 'weekend_rate_id'];


    public function weekdayRate()
    {
        return $this->belongsTo(EntranceRate::class, 'weekday_rate_id');
    }

    public function weekendRate()
    {
        return $this->belongsTo(EntranceRate::class, 'weekend_rate_id');
    }
}
