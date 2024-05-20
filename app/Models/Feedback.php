<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feedback extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'feedbacks';
    protected $fillable = [
        'room_id',
        'user_id',
        'content',
        'create_by',
        'update_by',
        'delete_by',
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function room(){
        return $this->belongsTo(Room::class);
    }
}
