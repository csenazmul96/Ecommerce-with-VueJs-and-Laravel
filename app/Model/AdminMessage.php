<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminMessage extends Model
{
    protected $guarded =[];

    public function user() {
        return $this->belongsTo('App\Model\User')->with('buyer');
    }
}
