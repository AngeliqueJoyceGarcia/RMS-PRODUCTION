<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EOD extends Model
{
    use HasFactory;

    protected $table = 'eod';

    protected $fillable = ['start_time', 'end_time'];

    public static $rules = [
        'start_time' => 'required|date_format:H:i',
        'end_time' => 'required|date_format:H:i|after:start_time',
    ];
}
