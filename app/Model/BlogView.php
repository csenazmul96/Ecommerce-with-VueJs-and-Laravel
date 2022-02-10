<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BlogView extends Model
{
    //
    protected $fillable = ['post_id','ip','view'];
}
