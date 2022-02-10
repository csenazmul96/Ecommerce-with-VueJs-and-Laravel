<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemReview extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id', 'item_id', 'rate', 'review', 'title', 'like', 'dislike'
    ];

    public function user() {
        return $this->belongsTo('App\Model\User')->with('buyer');
    }
    public function item() {
        return $this->belongsTo('App\Model\Item');
    }
    public function images() {
        return $this->hasMany('App\Model\ItemReviewImage', 'review_id');
    }
}
