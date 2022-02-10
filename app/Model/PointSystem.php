<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PointSystem extends Model
{
    protected $fillable = [
        'status', 'point_type', 'from_price_1', 'percentage_discount_1', 'unit_price_discount_1', 'free_shipping_1',
    ];
}
