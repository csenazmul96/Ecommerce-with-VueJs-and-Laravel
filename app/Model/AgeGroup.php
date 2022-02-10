<?php

namespace App\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class AgeGroup extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'lower_limit', 'upper_limit'
    ];
}
