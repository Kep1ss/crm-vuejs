<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable 
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    
    // CONST ROLE 
    const ROLE_SUPERADMIN = 0;
    const ROLE_MANAGER_NASIONAL = 1;
    const ROLE_MANAGER_AREA = 2;
    const ROLE_KAPER = 3;
    const ROLE_SPV = 4;
    const ROLE_SALES = 5;
    const ROLE_KOTELE = 6;
    const ROLE_TELE_MARKETING = 7;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function district(){
        return $this->belongsTo(District::class);
    }

    public function province(){
        return $this->belongsTo(Province::class);
    }

    public function city(){
        return $this->belongsTo(City::class);
    }

    public function parent(){
        return $this->belongsTo(User::class);
    }

    public function areas(){
        return $this->hasMany(Area::class);
    }
}
