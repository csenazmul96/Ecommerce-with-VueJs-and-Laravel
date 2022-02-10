<?php

namespace App\Http\Controllers;

use App\Model\BlogPost;
use App\Model\Item;
use App\Model\Category;
use App\Model\ItemImages;
use App\Model\MetaVendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SitemapController extends Controller
{
    public function index()
    {
        $itemsLastModify = Item::where('status', 1)->orderBy('updated_at', 'desc')->limit(1)->first();
        $vendorsLastModify = MetaVendor::where('active', 1)->where('verified', 1)->orderBy('updated_at', 'desc')->limit(1)->first();
        $categoriesModify = Category::where('parent', 0)->orderBy('updated_at', 'desc')->limit(1)->first();
        return response()->view('sitemap.index', [
            'itemsLastModify' => $itemsLastModify,
            'vendorsLastModify' => $vendorsLastModify,
            'categoriesModify' => $categoriesModify,
        ])->header('Content-Type', 'text/xml');
    }

    public function staticPages()
    {
        return response()->view('sitemap.static_pages', [])->header('Content-Type', 'text/xml');
    }
    public function blogs() {
        $blogs = BlogPost::where('status', 1)->latest()->get();

        return response()->view('sitemap.blogs', compact('blogs'))->header('Content-Type', 'text/xml');
    }

    public function items() {
        $items = Item::where('status', 1)->get();

        return response()->view('sitemap.items', [
            'items' => $items,
        ])->header('Content-Type', 'text/xml');
    }

    public function vendors() {
        $vendors = MetaVendor::where('verified', 1)
            ->where('active', 1)
            ->get();

        return response()->view('sitemap.vendors', [
            'vendors' => $vendors,
        ])->header('Content-Type', 'text/xml');
    }

    public function categories() {
        $categories = Category::where('parent', 0)->get();
        foreach ($categories as &$category) {
            $category->subCategories = Category::where('parent', $category->id)->get();
            foreach ($category->subCategories as $sub) {
                $sub->thirdcategory = Category::where('parent', $sub->id)->get();
            }
        }

        return response()->view('sitemap.categories', [
            'categories' => $categories,
        ])->header('Content-Type', 'text/xml');
    }

    public function convertImg()
    {
        $images = ItemImages::where('item_id', '!=', 0)->whereNull('compressed_image_jpg_path')->get();
        $originalImgPath = 'items/original/'.\Carbon\Carbon::now()->format('Y-m-d').'/';
        $copyFrom = null;

        foreach ($images as $image) {
            $saImgPath = str_replace(basename($image->image_path),"sa/".basename($image->image_path),$image->image_path);
            $saCompressImgPath = str_replace(basename($image->compressed_image_path),"sa/".basename($image->compressed_image_path),$image->compressed_image_path);

            if ($image->image_path && file_exists(public_path($image->image_path))) {
                $copyFrom = public_path($image->image_path);
            } elseif ($image->image_path && file_exists(public_path($saImgPath))) {
                $copyFrom = public_path($saImgPath);
            } elseif ($image->compressed_image_path && file_exists(public_path($image->compressed_image_path))) {
                $copyFrom = public_path($image->compressed_image_path);
            } elseif ($image->compressed_image_path && file_exists(public_path($saCompressImgPath))) {
                $copyFrom = public_path($saCompressImgPath);
            }

            if ($copyFrom) {
                $filename = Str::uuid() . '.' . pathinfo($copyFrom, PATHINFO_EXTENSION);
                Storage::put($originalImgPath . $filename, file_get_contents($copyFrom));

                $newPath = $originalImgPath . $filename;
                $paths = optimizedImage($newPath);

                $image->image_path = $newPath;
                $image->compressed_image_path = $paths['compressed_img_webp'];
                $image->compressed_image_jpg_path = $paths['compressed_img_jpg'];
                $image->thumbs_image_path = $paths['thumbs_img'];
                $image->save();
            } else {
                $image->delete();
            }
        }
    }
}
