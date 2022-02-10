<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Size extends Model
{
    //
	use SoftDeletes;

    protected $fillable = [
        'name', 'item_size', 'desc','status'
    ];
}
