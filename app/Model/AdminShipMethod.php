<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminShipMethod extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'courier_id', 'fee'
    ];

    public function courier() {
        return $this->belongsTo('App\Model\Courier');
    }
}
