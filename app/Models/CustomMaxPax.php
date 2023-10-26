<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomMaxPax extends Model
{
    use HasFactory;

    protected $table = 'custom_max_pax';
    protected $fillable = ['name', 'maximum_customers','event_date'];
}
