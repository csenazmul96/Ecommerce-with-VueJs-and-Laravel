<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ItemView extends Model
{
    protected $fillable = [
        'item_id', 'user_id','ip'
    ];

    public function item() {
        return $this->belongsTo('App\Model\Item', 'item_id')->with('images', 'vendor');
    }
}
