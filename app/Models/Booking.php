<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'bookings';
    public $fillable = [
        'user_id',
        'room_id',
        'check_in_date',
        'check_out_date',
        'create_by',
        'update_by',
        'delete_at',
        'delete_by',
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function room(){
        return $this->belongsTo(Room::class);
    }
    public function payment(){
        return $this->hasOne(Payment::class);
    }
    public function bill(){
        return $this->hasOne(Bill::class);
    }
    public function getAllBooking(){
        $bookings = Booking::with('user', 'room') -> get();
        return $bookings;
    } 
}
