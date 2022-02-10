<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use File;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class ItemImages extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'item_id', 'sort','color_id', 'image_path', 'compressed_image_path', 'compressed_image_jpg_path','thumbs_image_path'
    ];

    protected $appends = ['compressed_img_webp', 'compressed_img_jpg', 'thumbs_img'];

    public function getCompressedImgWebpAttribute()
    {
        return Storage::url($this->compressed_image_path);
    }

    public function getCompressedImgJpgAttribute()
    {
        return Storage::url($this->compressed_image_jpg_path);
    }

    public function getThumbsImgAttribute()
    {
        return Storage::url($this->thumbs_image_path);
    }

    public function color() {
        return $this->belongsTo('App\Model\Color', 'color_id');
    }

    protected static function boot() {
        parent::boot();
        static::deleting(function(ItemImages $image) {
            imageDelete($image->image_path, true);
            imageDelete($image->compressed_image_path, true);
            imageDelete($image->thumbs_image_path, true);
        });
    }

    public function scopeImageMoveToStorage($query, $count = 1) {
        $skip = max(($count-1)*1000, 0);
        $images = ItemImages::orderBy('id')->where('created_at', '<', '2020-06-05 00:00:00')->skip($skip)->take(200)->get();
        return 'check the code again.';
        foreach ($images as $image) {
            echo ' .';
            // problem on 11 > 65 . skip 10067
            // problem on 24 > 800 . skip 23800
            try {
                $tempImagePath = temporaryImageClone($image->image_path);
                $allPaths = imageMove($tempImagePath, $folderName = 'items', $imageStoreTypes = ['image_path', 'compressed_image_path', 'thumbs_image_path']);
                if ($image->compressed_image_path == null) {
                    continue;
                } else {
                    $image->image_path = $allPaths['image_path'];
                    $image->compressed_image_path = $allPaths['compressed_image_path'];
                    $image->thumbs_image_path = $allPaths['thumbs_image_path'];
                    $image->save();
                }
            } catch (\Exception $th) {
                echo $image->id . ' ';
                continue;
            }
        }
        echo "\n\n\n count: ".$count.", last id: ", $image['id'] ?? 'empty';
        echo "\n next count: ".($count+1);
        echo "\n completed";
        return;
    }
}
