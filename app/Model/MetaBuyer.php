<?php

namespace App\Model;

use App\Enumeration\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MetaBuyer extends Model
{
    use SoftDeletes;

    protected $table = 'meta_buyers';

    protected $fillable = [
        'verified', 'active', 'block', 'user_id', 'age_group_id', 'skin_type_id', 'shipping_location', 'shipping_address', 'shipping_city', 'shipping_state_id',
        'shipping_state', 'shipping_unit', 'shipping_zip', 'shipping_country_id', 'shipping_phone', 'shipping_fax', 'billing_location', 'billing_address', 'billing_city',
        'billing_state_id', 'billing_state', 'billing_unit', 'billing_zip' , 'billing_country_id', 'billing_phone', 'billing_fax', 'receive_offers', 'avatar',
        'points', 'points_spent'
    ];

    public function user() {
        return $this->hasOne('App\Model\User', 'buyer_meta_id');
    }

    public function ageGroup() {
        return $this->hasOne('App\Model\AgeGroup', 'id', 'age_group_id');
    }

    public function skinType() {
        return $this->hasOne('App\Model\SkinType', 'id', 'skin_type_id');
    }

    public function billingState() {
        return $this->belongsTo('App\Model\State', 'billing_state_id');
    }

    public function billingCountry() {
        return $this->belongsTo('App\Model\Country', 'billing_country_id');
    }

    public function orders() {
        return $this->hasManyThrough('App\Model\Order', 'App\Model\User', 'id', 'user_id', 'user_id')
            ->where('status', '!=', OrderStatus::$INIT);
    }

    public function login() {
        return $this->hasManyThrough('App\Model\LoginHistory', 'App\Model\User', 'id', 'user_id', 'user_id');
    }

    public function userLastLogin() {
        return $this->hasOne('App\Model\LoginHistory', 'user_id', 'user_id')->orderBy('created_at', 'DESC');
    }

    public static function avatar_path()
    {
        return 'images/avatar/';
    }
}
