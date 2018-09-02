<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = [
        'id', 'value', 'suite', 'deck_id', 'path'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}

