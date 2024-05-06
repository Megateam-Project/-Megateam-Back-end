<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class User extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'users';
    public $fillable = [
        'name',
        'email',
        'phone',
        'avatar',
        'role',
        'password',
        'create_by',
        'update_by',
        'delete_by',
        'create_at',
        'update_at'
    ];
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
