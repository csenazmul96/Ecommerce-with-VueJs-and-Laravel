<?php

namespace App\Http\Controllers;

use App\Model\Item;
use App\Model\Category;
use App\Enumeration\Role;
use App\Model\MasterColor;
use App\Model\CategoryTopBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class NewArrivalController extends Controller
{
    public function showItems(Request $request) {
        $category = Category::where('parent', '=', 0)->orderBy('sort')->get();
        $defaultItemImage = DB::table('settings')->where('name', 'default-item-image')->first();
        if ($defaultItemImage)
            $defaultItemImage_path = asset($defaultItemImage->value);

        $masterColors = MasterColor::orderBy('name')->get();

        $categoryBanner = CategoryTopBanner::whereNull('category_id')->latest()->first();

        $totalItems = Item::where('status', 1)
                    ->where('activated_at', '>=', date('Y-m-d', strtotime('-30 days')))
                    ->count();

        return view('pages.new_arrival', compact('defaultItemImage','defaultItemImage_path','category', 'masterColors', 'categoryBanner', 'totalItems'));
    }

    public function getNewArrivalItems(Request $request) {
        $query = Item::query();

        $query->where('status', 1);

        $query->where('activated_at', '>=', date('Y-m-d', strtotime('-30 days')));

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

        if ($request->masterColors && sizeof($request->masterColors) > 0) {
            $masterColors = $request->masterColors;

            $query->whereHas('colors', function ($query) use ($masterColors) {
                $query->whereIn('master_color_id', $masterColors);
            });
        }

        $items = $query->with('images', 'vendor', 'colors')->paginate(5);
        $paginationView = $items->links('others.pagination');
        $paginationView = trim(preg_replace('/\r\n/', ' ', $paginationView));

        // Blocked check
        $blockedVendorIds = [];

        foreach($items as &$item) {
            // Price
            $price = '';
            $colorsImages = [];

            foreach($item->colors as $color) {
                foreach ($item->images as $image) {
                    if ($image->color_id == $color->id) {
                        $colorsImages[$color->name] = asset($image->thumbs_image_path);
                        break;
                    }
                }
            }

            if (Auth::check() && Auth::user()->role == Role::$BUYER) {
                if ($item->orig_price != null)
                    $price .= '<del>$' . number_format($item->orig_price, 2, '.', '') . '</del> ';

                $price .= '$' . number_format($item->price, 2, '.', '');
            }


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
            $item->colorsImages = $colorsImages;
            $item->video = ($item->video) ? asset($item->video) : null;

            // Blocked Check
            if (in_array($item->vendor_meta_id, $blockedVendorIds)) {
                $item->imagePath = asset('images/blocked.jpg');
                $item->vendor->company_name = '';
                $item->vendorUrl = '';
                $item->style_no = '';
                $item->price = '';
                $item->available_on = '';
                $item->colors->splice(0);
            }
        }
        return ['items' => $items->toArray(), 'pagination' => $paginationView];
    }
}
