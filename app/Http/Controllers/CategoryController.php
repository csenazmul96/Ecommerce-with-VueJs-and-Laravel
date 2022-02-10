<?php

namespace App\Http\Controllers;

use Auth;
use App\Model\Item;
use App\Model\Category;
use App\Enumeration\Role;
use App\Model\MasterColor;
use App\Model\CategoryTopBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function getItemsCategory(Request $request) {
        $query = Item::query()->where('status', 1);

        if ($request->sorting){
            if ($request->sorting == '1')
                $query->orderBy('activated_at', 'desc');
            else if ($request->sorting == '2')
                $query->orderBy('price', 'desc');
            else if ($request->sorting == '3')
                $query->orderBy('price', 'asc');
            else if ($request->sorting == '4')
                $query->orderBy('activated_at', 'desc');
            else if ($request->sorting == '5')
                $query->orderBy('activated_at', 'asc');
        }else{
            $query->orderBy('activated_at', 'desc');
        }

        if ($request->categories && sizeof($request->categories) > 0)
            $query->where('default_parent_category', $request->categories)
                    ->orWhere('default_second_category', $request->categories);

        if ($request->masterColors && sizeof($request->masterColors) > 0) {
            $masterColors = $request->masterColors;
            $query->whereHas('colors', function ($query) use ($masterColors) {
                $query->whereIn('master_color_id', $masterColors);
            });
        }

        $items = $query->with('images', 'vendor', 'colors')->paginate(4);

        $paginationView = $items->links('others.pagination');

        $paginationView = trim(preg_replace('/\r\n/', ' ', $paginationView));

        foreach($items as &$item) {
            // Price
            $price = '';
            $colorsImages = [];

            if (Auth::check() && Auth::user()->role == Role::$BUYER) {
                if ($item->orig_price != null)
                    $price .= '<del>$' . number_format($item->orig_price, 2, '.', '') . '</del> ';

                $price .= '$' . number_format($item->price, 2, '.', '');
            }

            $colorsImages = [];

            foreach($item->colors as $color) {
                foreach ($item->images as $image) {
                    if ($image->color_id == $color->id) {
                        $colorsImages[$color->name] = asset($image->thumbs_image_path);
                        break;
                    }
                }
            }

            $item->colorsImages = $colorsImages;

            // Image
            $imagePath2 = '';
            $imagePath = asset('images/no-image.png');

            if (sizeof($item->images) > 0){
                $image_1 = $item->images[0]->compressed_image_path ?? $item->images[0]->image_path;
                $imagePath = asset($image_1);
            }

            if (sizeof($item->images) > 1)
                $imagePath2 = asset($item->images[1]->compressed_image_path);

            $item->imagePath = $imagePath;
            $item->imagePath2 = $imagePath2;
            $item->detailsUrl = route('product_single_page', ['item' => $item->id, 'name' => changeSpecialChar($item->name)]);

            $item->price = $price;
            $item->video = ($item->video) ? asset($item->video) : null;

        }

        return ['items' => $items->toArray(), 'pagination' => $paginationView];
    }

    public function singleCategory(Request $request, $slug, $slug2 = null)
    {   
        $current_cat = '';
        if ($slug2 == null) {
            $category = Category::where('parent', '=', 0)->where('slug', '=', $slug)->orderBy('sort')->first();
            $parent_category = Category::where('slug', '=', $slug)->where('parent', 0)->first();
            $current_cat = $parent_category;
        } else {
            if($slug == 'new-in'){
                $parent_category = Category::where('slug', '=', $slug)->where('parent', 0)->first();
                $category = Category::where('slug', '=', $slug2)->first();
            }else{
                $parent_category = Category::where('slug', '=', $slug)->where('parent', 0)->first();

                $category = Category::where('slug', '=', $slug2)->where('parent', $parent_category->id)->first();
            }

            $current_cat = $category;
        }  
        $defaultItemImage = DB::table('settings')->where('name', 'default-item-image')->first();
        if ($defaultItemImage)
            $defaultItemImage_path = asset($defaultItemImage->value);

        $allCategory = Category::where('parent', '=', 0)->where('id', '!=' ,$category->id)->whereNull('deleted_at')->orderBy('sort')->get();


        $masterColors = MasterColor::orderBy('name')->get();

        $categoryBanner = CategoryTopBanner::where('category_id',$category->id)->first();
        
        $totalItems = Item::where('status', 1)
                    ->where('default_parent_category', $category->id)
                 ->count();
        
        return view('pages.category', compact('category','current_cat', 'parent_category', 'masterColors', 'allCategory', 'categoryBanner', 'totalItems'));
    }

    public function singleCategoryPage(Request $request, $slug, $slug2 = null)
    {   
        if ($slug2 == null) {
            $category = Category::where('parent', '=', 0)->where('slug', '=', $slug)->orderBy('sort')->first();
            $parent_category = Category::where('slug', '=', $slug)->where('parent', 0)->first();
        } else {
            if($slug == 'new-in'){
                $parent_category = Category::where('slug', '=', $slug)->where('parent', 0)->first();
                $category = Category::where('slug', '=', $slug2)->first();
            }else{
                $parent_category = Category::where('slug', '=', $slug)->where('parent', 0)->first();

                $category = Category::where('slug', '=', $slug2)->where('parent', $parent_category->id)->first();
            }
        }

        
        $defaultItemImage = DB::table('settings')->where('name', 'default-item-image')->first();
        if ($defaultItemImage)
            $defaultItemImage_path = asset($defaultItemImage->value);

        
        $allCategory = Category::where('parent', '=', 0)->where('id', '!=' ,$category->id)->whereNull('deleted_at')->orderBy('sort')->get();

        $masterColors = MasterColor::orderBy('name')->get();

        $categoryBanner = CategoryTopBanner::where('category_id',$category->id)->first();

        return view('pages.category', compact('category', 'parent_category', 'masterColors', 'allCategory', 'categoryBanner'));
    }
}