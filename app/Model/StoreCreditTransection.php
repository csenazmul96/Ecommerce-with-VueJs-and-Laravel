<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreCreditTransection extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'vendor_meta_id', 'order_id', 'reason', 'amount'
    ];

    public function order() {
        return $this->belongsTo('App\Model\Order', 'order_id');
    }
}
