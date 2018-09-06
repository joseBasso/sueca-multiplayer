<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{

    protected $fillable = [
        'status',
        'total_players',
        'created_by',
        'deck_used'

    ];

    public function players()
    {
        return $this->belongsToMany(User::class,'game_user')->withPivot('team_number');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class);
    }


    public function team1()
    {
        return $this->belongsToMany('App\User')->wherePivot('team_number', 1);
    }

    public function team2()
    {
        return $this->belongsToMany('App\User')->wherePivot('team_number', 2);
    }

    public function scopeByTerminated($query){
        return $query->where('status','=','terminated');
    }

}
