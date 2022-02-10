<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CategoryColor extends Model
{
    protected $fillable = [
        'name', 'color_id', 'category_id', 'image', 'status'
    ];

    public function color(){
        return $this->belongsTo('App\Model\Color', 'color_id');
    }

    public function category(){
        return $this->belongsTo('App\Model\Category', 'category_id');
    }
}
