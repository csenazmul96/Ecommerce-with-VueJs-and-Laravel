<?php

namespace App\Model;

use App\Enumeration\OrderStatus;
use App\Enumeration\Role;
use Illuminate\Database\Eloquent\Model;

class MetaVendor extends Model
{
    protected $table = 'meta_vendors';

    protected $fillable = [
        'verified', 'active', 'user_id', 'company_name', 'business_name', 'website', 'business_category', 'year_established',
        'billing_location', 'billing_address', 'billing_unit', 'billing_city', 'billing_state_id', 'billing_state', 'billing_zip',
        'billing_country_id', 'billing_phone', 'billing_alternate_phone', 'billing_fax', 'billing_commercial', 'factory_location',
        'factory_address', 'factory_unit', 'factory_city', 'factory_state_id', 'factory_state', 'factory_zip', 'factory_country_id',
        'factory_phone', 'factory_alternate_phone', 'factory_evening_phone', 'factory_fax', 'factory_commercial', 'company_info',
        'hear_about_us', 'hear_about_us_other', 'receive_offers', 'size_chart', 'order_notice', 'setting_not_logged',
        'setting_unverified_user', 'setting_unverified_checkout', 'setting_sort_activation_date', 'setting_consolidation',
        'setting_estimated_shipping_charge', 'show_in_main_slider', 'show_in_mobile_main_slider', 'sp_vendor', 'sp_password',
        'sp_category', 'sp_default_category', 'min_order', 'return_policy','shipping'
    ];

    public function user() {
        return $this->hasOne('App\Model\User', 'vendor_meta_id', 'id')->where('role', Role::$VENDOR);
    }

    public function billingState() {
        return $this->belongsTo('App\Model\State', 'billing_state_id');
    }

    public function billingCountry() {
        return $this->belongsTo('App\Model\Country', 'billing_country_id');
    }

    public function parentCategories() {
        return $this->hasMany('App\Model\Category', 'vendor_meta_id')
            ->where('parent', 0)->orderBy('sort');
    }

    public function images() {
        return $this->hasMany('App\Model\VendorImage', 'vendor_meta_id');
    }

    public function items() {
        return $this->hasMany('App\Model\Item', 'vendor_meta_id')
            ->where('status',1)
            ->with('images');
    }

    public function orders() {
        return $this->hasMany('App\Model\Order', 'vendor_meta_id')->where('status', '!=', OrderStatus::$INIT);
    }

    public function shippingMethods() {
        return $this->hasMany('App\Model\ShippingMethod', 'vendor_meta_id')
            ->where('status', 1)
            ->with('courier', 'shipMethod');
    }
}
