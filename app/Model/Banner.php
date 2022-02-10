<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'image', 'type', 'link', 'category', 'page', 'name', 'description', 'sort'
    ];

    public function bannercategory()
    {
       return $this->belongsTo('App\Model\Category', 'category') ;
    }
}
