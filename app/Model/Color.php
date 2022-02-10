<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Color extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'status', 'master_color_id', 'color_code', 'image_path', 'thumbs_image_path'
    ];

    public function items()
    {
        return $this->belongsToMany('App\Model\Item');
    }
}
