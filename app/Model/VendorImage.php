<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorImage extends Model
{
    protected $fillable = [
        'type', 'image_path', 'status', 'sort', 'url', 'title','details'
    ];

   public function item() {
        return $this->hasOne('App\Model\Item', 'id', 'url');
   }

   public function item_image() {
        return $this->hasMany('App\Model\ItemImages', 'item_id', 'url')->orderBy('sort','asc');
   }
}
