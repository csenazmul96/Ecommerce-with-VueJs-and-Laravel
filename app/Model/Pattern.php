<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pattern extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'parent_category_id'
    ];

    public function category() {
        return $this->belongsTo('App\Model\Category', 'parent_category_id');
    }
}
