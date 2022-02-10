<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'status', 'style_no', 'name', 'slug', 'price', 'orig_price', 'sorting', 'details', 'video', 'youtube_url', 'specification', 'available_on', 'availability',
        'stock_quantity', 'brand_id', 'made_in_id', 'labeled', 'memo', 'view', 'total_in_cart', 'total_amount', 'total_order_qty','v_created_at',
        'c_created_at', 'o_created_at', 'activated_at'
    ];

    public function colors() {
        return $this->belongsToMany('App\Model\Color')->distinct('item_id')->withPivot('available');
    }

    public function views() {
        return $this->hasMany('App\Model\ItemView');
    }

    public function sizes() {
        return $this->belongsToMany('App\Model\Size')->distinct('item_id')->withPivot('available');
    }

    public function values() {
        return $this->belongsToMany('App\Model\ItemValue')->distinct('item_id');
    }

    // public function sizes() {
    //     return $this->hasMany('App\Model\ItemSize','item_id' );
    // }

    public function images() {
        return $this->hasMany('App\Model\ItemImages')->orderBy('sort');
    }

    public function category() {
        return $this->belongsTo('App\Model\Category')->withTrashed();
    }

    public function categories() {
        return $this->hasMany('App\Model\ItemCategory', 'item_id', 'id');
    }

    public function vendor() {
        return $this->belongsTo('App\Model\MetaVendor', 'vendor_meta_id', 'id');
    }

    public function carts() {
        return $this->hasMany('App\Model\CartItem','item_id', 'id');
    }

    public function brand() {
        return $this->belongsTo('App\Model\Brand','brand_id', 'id');
    }

    public function madeInCountry() {
        return $this->belongsTo('App\Model\MadeInCountry', 'made_in_id');
    }

    public function itemcategory() {
        return $this->hasMany('App\Model\ItemCategory','item_id','id');
    }

    public function itemcategories() {
		return $this->hasMany('App\Model\ItemCategory')->orderBy('id')->with('default_parent_category');
	}

    // protected static function boot() {
    //     parent::boot();
    //     static::deleting(function(Item $item) {
    //         foreach ($item->images as $image)
    //             $image->delete();
    //     });
    // }

    public function orders() {
        return $this->hasMany('App\Model\OrderItem','item_id','id')->select('id','item_id');
    }

    public function itemOrders() {
        return $this->hasMany('App\Model\OrderItem','item_id', 'id')->with('order');
    }

    public function scopeResetSorting($query) {
        $totalItem = Item::where('status', 1)->count();
        $items = Item::where('status', 1)->orderBy('sorting', 'desc')->get();
        foreach ($items as $item) {
            $item->sorting = $totalItem;
            $item->update();
            $totalItem--;
        }
    }
}
