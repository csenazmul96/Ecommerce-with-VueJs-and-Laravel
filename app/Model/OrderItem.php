<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';

    protected $fillable = [
        'order_id', 'item_id', 'color', 'size','color_name','size_name', 'item_per_pack', 'pack', 'qty', 'total_qty', 'per_unit_price', 'amount',
        'style_no', 'dispatch'
    ];

    public function item() {
        return $this->belongsTo('App\Model\Item')->withTrashed();
    }

    public function itemSize() {
        return $this->belongsTo('App\Model\Size','size', 'id');
    }

    public function order() {
        return $this->belongsTo('App\Model\Order');
    }
    public function statistic_color() {
        return $this->belongsTo('App\Model\Color','color');
    }
    public function statistic_size() {
        return $this->belongsTo('App\Model\Size','size');
    }

    public function itemWithoutTrashed() {
        return $this->belongsTo('App\Model\Item', 'item_id', 'id');
    }
}
