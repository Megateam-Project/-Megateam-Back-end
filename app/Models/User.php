<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Tymon\JWTAuth\Contracts\JWTSubject;
class User extends Authenticatable implements JWTSubject
{
    use HasFactory, SoftDeletes;    
    protected $table = 'users';
    protected $fillable = [
        'name',
        'email',
        'phone',
        'avatar',
        'password',
        'email',
        'role',
        'create_by',
        'update_by',
        'delete_by',
        // Add any other attributes you want to mass-assign here
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
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
}
