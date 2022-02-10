<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    protected $fillable = [
        'page', 'category', 'title', 'description', 'image'
    ];
}
