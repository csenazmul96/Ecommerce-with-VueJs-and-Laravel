<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MasterColor extends Model
{
    protected $fillable = [
        'name', 'image_path','color_code'
    ];

    public function colors()
    {
        return $this->hasMany('App\Model\Color');
    }
}
