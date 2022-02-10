<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Courier extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name'
    ];

    public function shipMethods() {
        return $this->hasMany('App\Model\AdminShipMethod');
    }
}
