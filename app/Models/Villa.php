<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Villa extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'villaname',
        'pricing',
        'capacity',
        'description',
        'images',
        'status_id',
    ];

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
}
