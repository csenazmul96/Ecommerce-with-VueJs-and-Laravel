<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogCategory extends Model
{
    protected $table = 'blog_categories';

    use SoftDeletes;

    protected $fillable = [ 
        'name','slug', 'sort','status'
    ];
}
