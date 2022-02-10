<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CartCoupon extends Model
{    
    protected $fillable = [
        'user_id', 'coupon_code', 'coupon_details', 'discount', 'free_shipping'
    ];
    protected $hidden = [
        'created_at', 'updated_at',
    ];
}