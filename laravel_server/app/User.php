<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'nickname', 'admin',  'blocked', 'reason_blocked', 'reason_reactivated'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function scopeByIdentifier($query,$identifier){
        return $query->orWhere('email', $identifier)->orWhere('nickname', $identifier)->first();
    }
}
