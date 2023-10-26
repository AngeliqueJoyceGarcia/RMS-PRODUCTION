<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'rooms';
    protected $fillable = [
        'roomname',
        'roomprice',
        'roomcapacity',
        'roomdescription',
        'images',
        'status_id',
    ];

    public $incrementing = true;

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

}
