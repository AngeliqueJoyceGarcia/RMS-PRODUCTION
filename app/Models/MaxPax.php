<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaxPax extends Model
{
    use HasFactory;

    protected $table = 'max_pax';
    protected $fillable = ['name', 'maximum_customers'];

}
