<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'price',
        'description',
        'image',
        'convenient',
        'number',
        'discount',
        'create_by',
        'update_by',
        'delete_by'
    ];

    protected $dates = ['deleted_at'];

    public function favorite(){
        return $this->hasMany(Favorite::class);
    }
    public function booking(){
        return $this->hasMany(Booking::class);
    }
    public function feedback(){
        return $this->hasMany(Feedback::class);
    }
}


