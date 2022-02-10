<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\User;

class CartItem extends Model
{

    protected $fillable = [
        'user_id', 'item_id', 'size_id','color_id','quantity'
    ];

    public function item() {
        return $this->belongsTo('App\Model\Item')->with('images', 'vendor','brand');
    }

    public function user()
    {
        return $this->belongsTo('App\Model\User');
    }

    public function itemsize() {
        return $this->belongsTo('App\Model\Size','size_id','id');
    }

    public function color() {
        return $this->belongsTo('App\Model\Color');
    }
}
