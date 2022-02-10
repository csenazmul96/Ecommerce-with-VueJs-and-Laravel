<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ItemValue extends Model
{
    protected $table = 'item_values';

    protected $fillable = [
        'icon', 'name', 'description', 'link', 'status','image_path'
    ];
}
