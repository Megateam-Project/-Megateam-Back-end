<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Favorite extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'favorites';
    public $fillable = [
        'user_id',
        'room_id',
        'create_by',
        'update_by',
        'delete_by',
        'create_at',
        'update_at'
    ] ;
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function room(){
        return $this->belongsTo(Room::class);
    }
}
