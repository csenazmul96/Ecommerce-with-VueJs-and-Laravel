<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Category extends Model
{
    use SoftDeletes;

    protected $table = 'categories';
    protected $fillable = [
        'name', 'slug', 'sub_title', 'description', 'parent', 'sort', 'status', 'image'
    ];

    public function parentCategory() {
        return $this->belongsTo('App\Model\Category', 'parent');
    }

    public function subCategories() {
        return $this->hasMany('App\Model\Category', 'parent', 'id')->orderBy('sort')->orderBy('name');
    }

    public function bodySizes() {
        return $this->hasMany('App\Model\BodySize', 'parent_category_id', 'id');
    }

    public function patterns() {
        return $this->hasMany('App\Model\Pattern', 'parent_category_id', 'id');
    }

    public function lengths() {
        return $this->hasMany('App\Model\Length', 'sub_category_id', 'id');
    }

    public function meta() {
        return $this->hasOne('App\Model\Meta', 'category', 'id');
    }

    public function banners() {
        return $this->hasMany('App\Model\Banner', 'category', 'id')->orderBy('sort');
    }

    public function colorbanners() {
        return $this->hasMany('App\Model\CategoryColor', 'category_id', 'id');
    }

    public static function CategoryWithMeta($id=null){
        return DB::table('categories')
            ->join('metas', 'categories.id', '=', 'metas.category')
            ->where('metas.category','=',$id)
            ->select('metas.id', 'metas.title', 'metas.description','categories.id')
            ->first();
    }


}
