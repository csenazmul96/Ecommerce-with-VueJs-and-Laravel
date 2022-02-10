<?php

namespace App\Model;

use DB;
use Mail;
use App\Enumeration\OrderStatus;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'user_name', 'password', 'role', 'active', 'buyer_meta_id','last_login', 'vendor_meta_id',
        'reset_token', 'order_count', 'login_count', 'facebook_id', 'provider', 'provider_id'
    ];


    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function vendor() {
        return $this->hasOne('App\Model\MetaVendor', 'id', 'vendor_meta_id');
    }

    public function buyer() {
        return $this->hasOne('App\Model\MetaBuyer', 'id', 'buyer_meta_id')
            ->with('billingState', 'billingCountry');
    }

    public function permissions() {
        $permissions = [];

        $obj = DB::table('user_permission')->where('user_id', $this->id)->get();

        foreach($obj as $item)
            $permissions[] = $item->permission;

        return $permissions;
    }

    public function storeCredit() {
        $sc = 0;

        $storeCredit = StoreCredit::where('user_id', Auth::user()->id)->first();

        if ($storeCredit)
            $sc = $storeCredit->amount;

        return $sc;
    }

    public function getNameAttribute() {
        return $this->first_name.' '.$this->last_name;
    }

    public function orders() {
        return $this->hasMany('App\Model\Order', 'user_id')->where('status', '!=', OrderStatus::$INIT);
    }

    public function buyerShippinAddress()
    {
        return $this->hasOne('App\Model\BuyerShippingAddress', 'user_id', 'id');
    }

    public function buyerShipping()
    {
        return $this->hasOne('App\Model\BuyerShippingAddress', 'user_id', 'id');
    }
}
