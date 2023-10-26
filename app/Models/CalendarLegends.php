<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarLegends extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'color'];

    // Define a method to update or create records based on unique 'name'
    public static function updateOrCreateLegend($name, $color)
    {
        return static::updateOrCreate(['name' => $name], ['color' => $color]);
    }
}
