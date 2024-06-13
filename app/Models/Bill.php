<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bill extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'bills';
    public $fillable = [
        'booking_id',
        'total_price',
        'date',
        'create_by',
        'update_by',
        'delete_at',
        'delete_by',
    ];
}
