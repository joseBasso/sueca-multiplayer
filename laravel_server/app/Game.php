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
        return $this->belongsToMany(User::class,'game_user','game_id','user_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class);
    }


    public function scopeByTerminated($query){
        return $query->where('status','=','terminated');
    }

}
