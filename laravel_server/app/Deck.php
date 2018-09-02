<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Deck extends Model
{
    protected $fillable = [
        'id', 'name', 'hidden_face_image_path', 'active', 'complete'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

}
