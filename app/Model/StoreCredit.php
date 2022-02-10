<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreCredit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'vendor_meta_id', 'amount'
    ];

    public function user() {
        return $this->belongsTo('App\Model\User', 'user_id')->with('buyer');
    }
}
