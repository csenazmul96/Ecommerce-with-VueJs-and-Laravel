<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogComment extends Model
{
    protected $table = 'blog_comments';

    use SoftDeletes;

    protected $fillable = [ 
        'post_id', 'status', 'name','email', 'comment', 'parent_comment', 'replay_comment_id', 'comment_level'
    ];
}
