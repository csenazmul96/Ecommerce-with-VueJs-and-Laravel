<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemReviewImage extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'review_id', 'image_path', 'compressed_image_path', 'thumbs_image_path'
    ];
}
