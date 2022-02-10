<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $fillable = [
        'url', 'ip', 'user_id', 'route_name'
    ];
}
