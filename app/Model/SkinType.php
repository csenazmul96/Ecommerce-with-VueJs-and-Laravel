<?php

namespace App\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class SkinType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type'
    ];
}
