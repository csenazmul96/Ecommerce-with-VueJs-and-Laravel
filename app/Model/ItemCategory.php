<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ItemCategory extends Model
{
    protected $table = 'item_categories';

    protected $fillable = [
        'item_id', 'default_parent_category', 'default_second_category', 'default_third_category'
    ];

    public function parent_category () {
        return $this->belongsTo('App\Model\Category', 'default_parent_category')->withTrashed();;
    }

    public function second_category() {
        return $this->belongsTo('App\Model\Category', 'default_second_category')->withTrashed();;
    }

    public function third_category() {
        return $this->belongsTo('App\Model\Category', 'default_third_category')->withTrashed();;
    }

    public function images() {
        return $this->hasMany('App\Model\ItemImages', 'item_id', 'item_id')->orderBy('sort');
    }

    public function item() {
        return $this->hasMany('App\Model\Item', 'id', 'item_id')->where('status', 1);
    }

    public static function getItemData(){
        $value=DB::table('items')->orderBy('price', 'asc')->get();
        return $value;
    }
}
