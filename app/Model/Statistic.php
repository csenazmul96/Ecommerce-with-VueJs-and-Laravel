<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    protected $fillable =[
        'item_id', 'user_id', 'color_id', 'size_id', 'status', 'cart_id', 'order_id', 'view', 'incart', 'qty', 'price', 'total_price'
    ];

    public function item() {
        return $this->belongsTo('App\Model\Item', 'item_id')->with('images');
    }

    public function itemCategory() {
        return $this->belongsTo('App\Model\ItemCategory', 'item_id');
    }

    public function item_color() {
        return $this->hasMany('App\Models\Color', 'id', 'color_id');
    }
}
