<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
        'user_id', 'promotion_type', 'status', 'title', 'description', 'is_permanent', 'valid_from', 'valid_to', 'hasCouponCode', 'coupon_code', 'from_price_1', 'to_price_1', 'percentage_discount_1', 'unit_price_discount_1', 'free_shipping_1', 'from_price_2', 'to_price_2', 'percentage_discount_2', 'unit_price_discount_2', 'free_shipping_2', 'from_price_3', 'to_price_3', 'percentage_discount_3', 'unit_price_discount_3', 'free_shipping_3', 'from_price_4', 'to_price_4', 'percentage_discount_4', 'unit_price_discount_4', 'free_shipping_4', 'from_price_5', 'to_price_5', 'percentage_discount_5', 'unit_price_discount_5', 'free_shipping_5'
    ];
}