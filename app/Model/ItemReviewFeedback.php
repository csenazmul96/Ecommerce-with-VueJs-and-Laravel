<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ItemReviewFeedback extends Model
{
    protected $fillable = [
        'user_id', 'item_review_id', 'like'
    ];
}
