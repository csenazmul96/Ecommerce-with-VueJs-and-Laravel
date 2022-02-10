<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BuyerAddress extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'set_default', 'store_no', 'location', 'address', 'unit', 'city', 'state_id', 'state_text', 'zip', 'country_id',
        'phone', 'fax', 'commercial', 'first_name', 'last_name', 'address2'
    ];

    public function state() {
        return $this->belongsTo('App\Model\State', 'state_id');
    }

    public function country() {
        return $this->belongsTo('App\Model\Country', 'country_id');
    }
}
