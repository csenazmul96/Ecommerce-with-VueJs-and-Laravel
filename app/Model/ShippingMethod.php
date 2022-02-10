<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingMethod extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'status', 'default', 'type', 'courier_id', 'ship_method_id', 'list_order'
    ];

    public function courier() {
        return $this->belongsTo('App\Model\Courier');
    }

    public function shipMethod() {
        return $this->belongsTo('App\Model\AdminShipMethod', 'ship_method_id');
    }
}
