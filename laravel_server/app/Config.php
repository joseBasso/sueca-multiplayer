<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $table = 'config';

    protected $fillable = [
        'platform_email', 'platform_email_properties', 'img_base_path'
    ];

}
