<?php

namespace App\Http\Controllers\Admin;

use App\Model\CategoryColor;
use App\Model\Category;
use App\Model\Color;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Uuid;
use Image;

class CategoryColorController extends Controller
{
    public function index()
    {
        $banners = CategoryColor::with('category','color')->get();
        $colors = Color::orderBy('name')->get();
        $categories = Category::where('parent', 0)->get();
        return view('admin.dashboard.category_color.index', compact('banners', 'colors', 'categories'))->with('page_title', 'Category Color');
    }


    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|mimes:jpg,jpeg,png,webp',
            'color_id' => 'required',
            'name' => 'required',
            'category_id' => 'required',
        ]);
        $imagepath= null;
        if ($request->hasFile('image')) {
            $image = request()->file('image');
            $imageName = Uuid::generate()->string;
            $imagepath = '/images/category_image/' . $imageName . '.webp';
            Image::make($image)->encode('webp', 80)->save(public_path('/images/category_image/' . $imageName . '.webp'));
        }
        $banner = new CategoryColor();
        $banner->name = $request->name;
        $banner->color_id = $request->color_id;
        $banner->category_id = $request->category_id;
        $banner->status = $request->status;
        $banner->image = $imagepath;
        $banner->save();

        return redirect()->route('category_color');
    }

    public function update(Request $request, CategoryColor $categoryColor)
    {
        $request->validate([
            'image' => 'mimes:jpg,jpeg,png,webp',
            'color_id' => 'required',
            'name' => 'required',
            'category_id' => 'required',
        ]);
        $banner = CategoryColor::find($request->bannerid);
        $imagepath= $banner->image;
        if ($request->hasFile('image')) {
            $image = request()->file('image');
            $imageName = Uuid::generate()->string;
            $imagepath = '/images/category_image/' . $imageName . '.webp';
            Image::make($image)->encode('webp', 80)->save(public_path('/images/category_image/' . $imageName . '.webp'));
            if (\File::exists(public_path($banner->image))) {
                \File::delete(public_path($banner->image));
            }
        }
        $banner->name = $request->name;
        $banner->color_id = $request->color_id;
        $banner->category_id = $request->category_id;
        $banner->status = $request->status;
        $banner->image = $imagepath;
        $banner->save();

        return redirect()->route('category_color');
    }

    public function destroy(Request $request)
    {
        $banner = CategoryColor::find($request->id);
        if (\File::exists(public_path($banner->image))) {
            \File::delete(public_path($banner->image));
        }
        $banner->delete();
    }
}
