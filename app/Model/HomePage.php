<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class HomePage extends Model
{
    protected $fillable = [
        'section_id', 'content'
    ];
}
