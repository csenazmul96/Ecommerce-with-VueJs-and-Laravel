<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    protected $table = 'blog_posts';
    use SoftDeletes;

    protected $fillable = [
        'status','title','image', 'thumb', 'image_alt', 'description', 'tags','slug', 'category_id', 'view', 'meta_title','meta_description'
    ];

    public function comments() {
        return $this->hasMany('App\Model\BlogComment','post_id','id')->where('status',1);
    }

    public function categories() {
        return $this->belongsTo('App\Model\BlogCategory', 'category_id', 'id');
    }
}
