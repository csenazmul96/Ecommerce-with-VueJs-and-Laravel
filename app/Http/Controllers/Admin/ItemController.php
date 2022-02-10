<?php

namespace App\Http\Controllers\Admin;

use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use URL;
use File;
use Uuid;
use Excel;
use Image;
use DateTime;
use Carbon\Carbon;
use App\Model\Item;
use App\Model\Size;
use App\Model\Brand;
use App\Model\Color;
use App\Model\ItemInv;
use GuzzleHttp\Client;
use App\Model\CartItem;
use App\Model\Category;
use App\Model\ItemSize;
use App\Model\ItemView;
use App\Model\ItemValue;
use App\Model\ItemImages;
use App\Model\MetaVendor;
use App\Model\MasterColor;
use App\Model\VendorImage;
use App\Model\ItemCategory;
use App\Model\WishListItem;
use App\Model\MadeInCountry;
use Illuminate\Http\Request;
use App\Enumeration\Availability;
use App\Enumeration\VendorImageType;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    public function createNewItemIndex() {
        $defaultCategories = [];

        $categoriesCollection = Category::orderBy('sort')->orderBy('name')->get();

        foreach($categoriesCollection as $cc) {
            if ($cc->parent == 0) {
                $data = [
                    'id' => $cc->id,
                    'name' => $cc->name
                ];

                $subCategories = [];
                foreach($categoriesCollection as $item) {
                    if ($item->parent == $cc->id) {
                        $data2 = [
                            'id' => $item->id,
                            'name' => $item->name
                        ];

                        $data3 = [];
                        foreach($categoriesCollection as $item2) {
                            if ($item2->parent == $item->id) {
                                $data3[] = [
                                    'id' => $item2->id,
                                    'name' => $item2->name
                                ];
                            }
                        }

                        $data2['subCategories'] = $data3;
                        $subCategories[] = $data2;
                    }
                }

                $data['subCategories'] = $subCategories;
                $defaultCategories[] = $data;
            }
        }

        $madeInCountries = MadeInCountry::where('status', 1)->orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $masterColors = MasterColor::orderBy('name')->get();
        $colors = Color::where('status', 1)->orderBy('name')->get();
        $sizes = Size::where('status', 1)->orderBy('name')->get();
        $itemValues = ItemValue::where('status', 1)->orderBy('name')->get();

        return view('admin.dashboard.create_new_item.index', compact('defaultCategories', 'brands', 'madeInCountries', 'masterColors', 'colors', 'sizes', 'itemValues'))->with('page_title', 'Create a New Item');
    }

    public function createNewItemPost(Request $request) {
        ini_set('upload_max_filesize', '5M');
        ini_set('post_max_size', '5M');

        Validator::extend('numericarray', function($attribute, $value, $parameters)
        {
            foreach($value as $v) {
                if($v==null) return false;
            }
            return true;
        });

        if($request->specification == 1 || $request->specification == 2){
            $request->validate([
                'style_no' => 'required|max: 255|unique:items,style_no',
                'item_name' => 'required|max: 255',
                'price' => 'required|regex:/^-?[0-9]+(?:\.[0-9]{1,2})?$/',
                'orig_price' => 'nullable|numeric|min:'.$request->price,
                'description' => 'nullable|max:500',
                'd_parent_category' => 'required|numericarray',
                'memo' => 'nullable|max:255',
                'colors' => 'required',
                'video' => 'nullable|mimes:mp4',
//                'brand' => 'required'
            ]);
        }else if($request->specification == 3){
            $request->validate([
                'style_no' => 'required|max: 255|unique:items,style_no',
                'item_name' => 'required|max: 255',
                'price' => 'required|regex:/^-?[0-9]+(?:\.[0-9]{1,2})?$/',
                'orig_price' => 'nullable|numeric|min:'.$request->price,
                'description' => 'nullable',
                'd_parent_category' => 'required|numericarray',
                'memo' => 'nullable|max:255',
                'sizes' => 'required',
                'video' => 'nullable|mimes:mp4',
//                'brand' => 'required'
            ]);
        }else{
            $request->validate([
                'style_no' => 'required|max: 255|unique:items,style_no',
                'item_name' => 'required|max: 255',
                'price' => 'required|regex:/^-?[0-9]+(?:\.[0-9]{1,2})?$/',
                'orig_price' => 'nullable|numeric|min:'.$request->price,
                'description' => 'nullable',
                'd_parent_category' => 'required|numericarray',
                'memo' => 'nullable|max:255',
                'video' => 'nullable|mimes:mp4',
//                'brand' => 'required'
            ]);
        }

        $videoPath = null;
        if ($request->video) {
            $filename = Uuid::generate()->string;
            $file = $request->file('video');
            $ext = $file->getClientOriginalExtension();
            $destinationPath = '/videos';
            $file->move(public_path($destinationPath), $filename.".".$ext);
            $videoPath = $destinationPath."/".$filename.".".$ext;
        }

        // Create slug from categoryname
        $slug = $this->slugGenerator($request->item_name, $request->style_no , $request->d_parent_category);

        if ($request->status == '1'){
            $activated_at = Carbon::now()->toDateTimeString();
        }else{
            $activated_at = null;
        }

        $itemCount = Item::count() + 1;

        $item = Item::create([
            'status' => $request->status,
            'style_no' => $request->style_no,
            'price' => $request->price,
            'orig_price' => $request->orig_price,
            'sorting' => $itemCount,
            'details' => $request->description,
            'name' => $request->item_name,
            'slug' => $slug,
            'made_in_id' => $request->made_in,
            'labeled' => $request->labeled,
//            'brand_id' => $request->brand,
            'memo' => $request->memo,
            'youtube_url' => $request->youtube_url,
            'specification' => $request->specification,
            'activated_at' => $activated_at,
            'video' => $videoPath,
        ]);

        $c = 0;
        foreach ($request->d_parent_category as $cat) {
            ItemCategory::create([
                'item_id' => $item->id,
                'default_parent_category' => $cat,
                'default_second_category' => $request->d_second_parent_category[$c],
                'default_third_category' => $request->d_third_parent_category[$c],
            ]);

            $c++;
        }

        if(isset($request->item_value) && count($request->item_value) > 0){
            $item->values()->attach($request->item_value);
        }

        if($request->specification == 1 || $request->specification == 2){
            $colorAttach = [];
            foreach ($request->colors as $color) {
                $var = 'color_available_'.$color;

                $colorAttach[$color] = [
                    'available' => ($request->$var ? 1 : 0)
                ];
            }

            $item->colors()->attach($colorAttach);
        }


        if($request->specification == 1 || $request->specification == 3){

            if($request->specification == 1){
                if(isset($request->inv) && count($request->inv) > 0){
                    $sizeAttach = [];
                    foreach ($request->inv as $inv){
                        $sizeAttach[$inv['color_size']] = [
                            'available' => 1
                        ];
                    }
                    $item->sizes()->attach($sizeAttach);
                }
            }else{
                $sizeAttach = [];
                foreach ($request->sizes as $size) {
                    $var = 'size_available_'.$size;

                    $sizeAttach[$size] = [
                        'available' => ($request->$var ? 1 : 0)
                    ];
                }

                $item->sizes()->attach($sizeAttach);
            }
        }

        $itemInvIds = [];
        if(isset($request->inv) && count($request->inv) > 0){
            foreach ($request->inv as $inv){
                $itemInvModel = new ItemInv();
                $itemInvModel->item_id = $item->id;
                if($request->specification == 1 || $request->specification == 2){
                    $itemInvModel->color_id = $inv['color_id'];
                    $itemInvModel->color_name = $inv['color_name'];
                }

                $itemInvModel->qty = $inv['qty'];

                if($request->specification == 1){
                    $itemInvModel->itemsize = $inv['color_size'];
                }

                if($request->specification == 3){
                    $itemInvModel->itemsize = $inv['size_id'];
                }


                /*if($request->specification != 4){
                    $itemInvModel->sort = $inv['sort']+1;
                }*/

                if(isset($inv['sort'])){
                    $itemInvModel->sort = $inv['sort']+1;
                }

                $itemInvModel->available_on = $inv['availability_inv'];
                $itemInvModel->created_at = Carbon::now();
                $itemInvModel->save();
                $itemInvIds[] = $itemInvModel->id;
            }
        }
        $itemInvModel = new ItemInv();
        $itemInvModel->where('item_id', $item->id)->whereNotIn('id', $itemInvIds)->delete();

        if ($request->imagesId) {
            for ($i = 0; $i < sizeof($request->imagesId); $i++) {
                $image = ItemImages::where('id', $request->imagesId[$i])->first();

                $paths = optimizedImage($image->image_path);

                if($request->specification == 1 || $request->specification == 2){
                    $image->color_id = $request->imageColor[$i];
                }

                $image->item_id = $item->id;
                $image->sort = $i + 1;
                $image->compressed_image_path = $paths['compressed_img_webp'];
                $image->compressed_image_jpg_path = $paths['compressed_img_jpg'];
                $image->thumbs_image_path = $paths['thumbs_img'];
                $image->save();
            }
        }

        return redirect()->route('admin_item_list_all');
    }

    public function addColor(Request $request) {
        if ($request->id == '' || $request->name == '')
            return response()->json(['success' => false, 'message' => 'Invalid parameters.']);

        $mc = Color::where('name', $request->name)->first();

        if ($mc)
            return response()->json(['success' => false, 'message' => 'Already have this color.']);

        $mc = Color::create([
            'name' => $request->name,
            'status' => 1,
            'master_color_id' => $request->id,
            'color_code' => $request->color_code,
        ]);

        return response()->json(['success' => true, 'color' => $mc->toArray()]);
    }
    public function addMasterColor(Request $request) {
        if ($request->color_code == '' || $request->name == '')
            return response()->json(['success' => false, 'message' => 'Invalid parameters.']);

        $mc = Color::where('name', $request->name)->first();

        if ($mc)
            return response()->json(['success' => false, 'message' => 'Already have this color.']);

        $mc = MasterColor::create([
            'name' => $request->name,
            'status' => 1,
            'color_code' => $request->color_code,
        ]);

        return response()->json(['success' => true, 'color' => $mc->toArray()]);
    }

    public function uploadImage(Request $request) {
        //$image = $request->file('file');
        //$temporaryPath = temporaryImageUpload($image);

        $image = ItemImages::create([
            'image_path' => $request->file('file')->store('items/original/'.Carbon::now()->format('Y-m-d'))
        ]);

        $image->fullPath = Storage::url($image->image_path);

        return response()->json(['success' => true, 'data' => $image->toArray()]);
    }

    public function itemListAll(Request $request) {
        $activeItemsQuery = Item::query();
        $activeItemsQuery->where('status', 1)
            ->with('category', 'images');

        //Active Item Search
        if ($request->text){
            $data = explode(',', $request->text);
            $activeItemsQuery->where(function ( $q) use ($data, $request){
                if (isset($request->style) && $request->style == '1') {
                    foreach ($data as $value){
                        $q->orWhere('style_no', 'like', '%' . ltrim($value, ' ') . '%');
                    }
                }

                if (isset($request->des) && $request->des == '1') {
                    $q->orWhere('details', 'like', '%' . $request->text . '%');
                }

                if (isset($request->name) && $request->name == '1') {
                    $q->orWhere('name', 'like', '%' . $request->text . '%');
                }
            });
        }

        // Active Items Order
        if (isset($request->s1) && $request->s1 != '') {
            if ($request->s1 == '4')
                $activeItemsQuery->orderBy('price');
            else if ($request->s1 == '1')
                $activeItemsQuery->orderBy('updated_at', 'desc');
            else if ($request->s1 == '2')
                $activeItemsQuery->orderBy('created_at', 'desc');
            else if ($request->s1 == '3')
                $activeItemsQuery->orderBy('activated_at', 'desc');
            else if ($request->s1 == '5')
                $activeItemsQuery->orderBy('price', 'desc');
            else if ($request->s1 == '6')
                $activeItemsQuery->orderBy('style_no');
            else if ($request->s1 == '0') {
                $activeItemsQuery->orderBy('sorting', 'desc');
                // $activeItemsQuery->orderBy('activated_at', 'desc');
            }
        } else {
            $activeItemsQuery->orderBy('sorting', 'desc');
            // $activeItemsQuery->orderBy('activated_at', 'desc');
        }

        // History
        DB::table('item_list_history')->delete();
        $historyData = [];
        $q = clone $activeItemsQuery;
        $q->select('id')->orderBy('id', 'asc');
        $tmp = $q->pluck('id')->toArray();

        foreach ($tmp as $t)
            $historyData[] = [
                'item_id' => $t,
                'status' => 1
            ];
        $totalActiveitem = $activeItemsQuery->count();
        $activeItems = $activeItemsQuery->paginate(50, ['*'], 'p1');

        // Inactive Items
        $inactiveItemsQuery = Item::query();
        $inactiveItemsQuery->where('status', 0)
            ->with('category', 'images');

        //Inactive Item Search
        if ($request->text){
            $data = explode(',', $request->text);
            $inactiveItemsQuery->where(function ( $q) use ($data, $request){
                if (isset($request->style) && $request->style == '1') {
                    foreach ($data as $value){
                        $q->orWhere('style_no', 'like', '%' . ltrim($value, ' ') . '%');
                    }
                }

                if (isset($request->des) && $request->des == '1') {
                    $q->orWhere('details', 'like', '%' . $request->text . '%');
                }

                if (isset($request->name) && $request->name == '1') {
                    $q->orWhere('name', 'like', '%' . $request->text . '%');
                }
            });
        }

        // Inactive order
        if (isset($request->s2) && $request->s2 != '') {
            if ($request->s2 == '4')
                $inactiveItemsQuery->orderBy('price');
            else if ($request->s2 == '1')
                $inactiveItemsQuery->orderBy('updated_at', 'desc');
            else if ($request->s2 == '2')
                $inactiveItemsQuery->orderBy('created_at', 'desc');
            else if ($request->s2 == '3')
                $inactiveItemsQuery->orderBy('activated_at', 'desc');
            else if ($request->s2 == '5')
                $inactiveItemsQuery->orderBy('price', 'desc');
            else if ($request->s2 == '6')
                $inactiveItemsQuery->orderBy('style_no');
            else if ($request->s2 == '0') {
                $activeItemsQuery->orderBy('sorting', 'desc');
                // $inactiveItemsQuery->orderBy('activated_at', 'desc');
            }
        } else {
            // $activeItemsQuery->orderBy('sorting', 'desc');
            $inactiveItemsQuery->orderBy('created_at', 'desc');
        }

        // History
        $q = clone $inactiveItemsQuery;
        $q->select('id');
        $tmp = $q->pluck('id')->toArray();

        foreach ($tmp as $t)
            $historyData[] = [
                'item_id' => $t,
                'status' => 0
            ];

        DB::table('item_list_history')->insert($historyData);
        $totalInactiveitem = $inactiveItemsQuery->count();
        $inactiveItems = $inactiveItemsQuery->paginate(50, ['*'], 'p2');

        $appends = [
            'p1' => $activeItems->currentPage(),
            'p2' => $inactiveItems->currentPage(),
        ];

        foreach ($request->all() as $key => $value) {
            if ($key != 'p1' && $key != 'p2')
                $appends[$key] = ($value == null) ? '' : $value;
        }

        $categories = Category::where('parent', 0)->orderBy('sort')->orderBy('name')->get();

        // Url history
        DB::table('item_list_url_history')->delete();
        DB::table('item_list_url_history')->insert([
            'url' => url()->full()
        ]);

        return view('admin.dashboard.item_list.index', compact( 'activeItems', 'inactiveItems', 'appends', 'categories', 'totalInactiveitem', 'totalActiveitem'))
            ->with('page_title', 'All Items');
    }

    public function categoryMove(Request $request) {
        $items = Item::whereIn('id', $request->ids)
            ->with('itemcategory')->get();

        $active_cat = Category::where('id', $request->cat_id)->first();

        if ($active_cat->parent == 0){
            foreach ($items as $item) {
                foreach($item->itemcategory as $category){
                    $category->update(['default_parent_category' => $request->cat_id,'default_second_category' => 0]);
                }
            }
        }else {
            foreach ($items as $item) {
                foreach($item->itemcategory as $category){
                    $category->update(['default_parent_category' => $active_cat->parent,'default_second_category' => $request->cat_id]);
                }
            }
        }
    }

    public function itemsChangeToInactive(Request $request) {
        Item::whereIn('id', $request->ids)->update(['status' => 0]);
    }

    public function itemsChangeToActive(Request $request) {
        $time = Carbon::now();

        $maxSort = Item::where('status', 1)->max('sorting');

        $itemCount = $maxSort + 1;

        Item::whereIn('id', $request->ids)->update([
            'status' => 1,
            'sorting' => $itemCount,
            'activated_at' => $time->toDateTimeString()
        ]);
    }

    public function itemsDelete(Request $request) {

        $images = ItemImages::whereIn('item_id',$request->ids)->get();
        foreach ($images as $image) {
            if (\File::exists(public_path().'/'.$image->image_path)) {
                \File::delete(public_path().'/'.$image->image_path);
            }
            if (\File::exists(public_path().'/'.$image->compressed_image_path)) {
                \File::delete(public_path().'/'.$image->compressed_image_path);
            }
            $image->image_path = null;
            $image->compressed_image_path = null;
            $image->save();
        }

        VendorImage::whereIn('url', $request->ids)->delete();
        CartItem::whereIn('item_id', $request->ids)->delete();
        WishListItem::whereIn('item_id', $request->ids)->delete();
        ItemView::whereIn('item_id', $request->ids)->delete();

        $items = Item::whereIn('id', $request->ids)->get();

        foreach ($items as $item) {
            $item->style_no = $item->style_no.'-delete-'.rand();
            $item->save();
            $item->delete();
        }

        $itemCategories = ItemCategory::whereIn('item_id', $request->ids)->get();

        foreach ($itemCategories as $itemCategory) {
            $itemCategory->delete();
        }
    }

    public function removeVideo(Request $request) {
        Item::where('id', $request->id)->update(['video' => NULL]);
        return response()->json(['success' => true, 'data' => 'Video remoted!']);
    }

    public function editItem(Item $item) {

        $item->load('colors', 'sizes', 'images', 'categories');

        $madeInCountries = MadeInCountry::where('status', 1)->orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $masterColors = MasterColor::orderBy('name')->get();
        $colors = Color::where('status', 1)->orderBy('name')->get();
        $sizes = Size::where('status', 1)->orderBy('name')->get();
        $itemValues = ItemValue::where('status', 1)->orderBy('name')->get();
        $inventorydata = DB::table('item_inv')
            ->where('item_inv.item_id',$item->id)
            ->get();

        // Images color id
        $imagesColorIds = [];
        foreach($item->images as $img)
            $imagesColorIds[] = $img->color_id;

        // Default Categories
        $defaultCategories = [];

        $categoriesCollection = Category::orderBy('sort')->orderBy('name')->get();

        foreach($categoriesCollection as $cc) {
            if ($cc->parent == 0) {
                $data = [
                    'id' => $cc->id,
                    'name' => $cc->name,
                    'slug' => $cc->slug
                ];

                $subCategories = [];
                foreach($categoriesCollection as $cat) {
                    if ($cat->parent == $cc->id) {
                        $data2 = [
                            'id' => $cat->id,
                            'name' => $cat->name,
                            'slug' => $cat->slug
                        ];

                        $data3 = [];
                        foreach($categoriesCollection as $item2) {
                            if ($item2->parent == $cat->id) {
                                $data3[] = [
                                    'id' => $item2->id,
                                    'name' => $item2->name,
                                    'slug' => $item2->slug
                                ];
                            }
                        }

                        $data2['subCategories'] = $data3;
                        $subCategories[] = $data2;
                    }
                }

                $data['subCategories'] = $subCategories;
                $defaultCategories[] = $data;
            }
        }

        if (session('message') == null) {
            session(['back_url' => URL::previous()]);
        }

        $item_id = $item->id;
        // Get previous item by this item id
        $prev_item = DB::table('items')->where('id', '<', $item_id)->where('deleted_at', null)->orderBy('id', 'DESC')->first();
        $prev_item = isset($prev_item->id) ? $prev_item->id : 0;
        // Get next item by this item id
        $next_item = DB::table('items')->where('id', '>', $item_id)->where('deleted_at', null)->first();
        $next_item = isset($next_item->id) ? $next_item->id : 0;

        // Next-Previous items
        $history = DB::table('item_list_history')->select('item_id')
            ->where('status', $item->status)
            ->orderBy('id')
            ->pluck('item_id')->toArray();

        $nextItemId = null;
        $prevItemId = null;

        $currentPosition = array_search($item->id, $history);

        if ($currentPosition != 0)
            $prevItemId = isset($history[$currentPosition-1]) ? $history[$currentPosition-1] : null;

        if ($currentPosition < count($history)-1)
            $nextItemId = isset($history[$currentPosition+1]) ?  $history[$currentPosition+1] : null;

        // Url history
        DB::table('item_list_url_history')->delete();
        DB::table('item_list_url_history')->insert([
            'url' => url()->previous()
        ]);

        $tmp = DB::table('item_list_url_history')->first();
        if ($tmp)
            $backUrl = $tmp->url;
        return view('admin.dashboard.item_list.edit_item', compact( 'prev_item', 'next_item','nextItemId', 'prevItemId', 'backUrl', 'madeInCountries',
            'brands', 'masterColors', 'colors', 'sizes', 'itemValues', 'inventorydata', 'imagesColorIds', 'defaultCategories','item'))
            ->with('page_title', 'Item Edit');
    }

    public function editItemPost(Item $item, Request $request) {
        Validator::extend('numericarray', function($attribute, $value, $parameters)
        {
            foreach($value as $v) {
                if($v==null) return false;
            }
            return true;
        });

        if($request->specification == 1 || $request->specification == 2){
            $request->validate([
                'style_no' => 'required|max: 255|unique:items,style_no,'.$item->id,
                'item_name' => 'required|max: 255',
                'price' => 'required|regex:/^-?[0-9]+(?:\.[0-9]{1,2})?$/',
                'orig_price' => 'nullable|numeric|min:'.$request->price,
                'description' => 'nullable',
                'd_parent_category' => 'required|numericarray',
                'memo' => 'nullable|max:255',
                'colors' => 'required',
                'video' => 'nullable|mimes:mp4',
//                'brand' => 'required'
            ]);
        }else if($request->specification == 3){
            $request->validate([
                'style_no' => 'required|max: 255|unique:items,style_no,'.$item->id,
                'item_name' => 'required|max: 255',
                'price' => 'required|regex:/^-?[0-9]+(?:\.[0-9]{1,2})?$/',
                'orig_price' => 'nullable|numeric|min:'.$request->price,
                'description' => 'nullable',
                'd_parent_category' => 'required|numericarray',
                'memo' => 'nullable|max:255',
                'sizes' => 'required',
                'video' => 'nullable|mimes:mp4',
//                'brand' => 'required'
            ]);
        }else{
            $request->validate([
                'style_no' => 'required|max: 255|unique:items,style_no,'.$item->id,
                'item_name' => 'required|max: 255',
                'price' => 'required|regex:/^-?[0-9]+(?:\.[0-9]{1,2})?$/',
                'orig_price' => 'nullable|numeric|min:'.$request->price,
                'description' => 'nullable',
                'd_parent_category' => 'required|numericarray',
                'memo' => 'nullable|max:255',
                'video' => 'nullable|mimes:mp4',
//                'brand' => 'required'
            ]);
        }

        $videoPath = 0;
        // Create slug from categoryname

        if ($request->video) {
            $filename = Uuid::generate()->string;
            $file = $request->file('video');
            $ext = $file->getClientOriginalExtension();

            $destinationPath = '/videos';
            $file->move(public_path($destinationPath), $filename.".".$ext);
            $videoPath = $destinationPath."/".$filename.".".$ext;
            $item->video = $videoPath;
        }

        if ($item->status == '0' && $request->status == '1')
            $item->activated_at = Carbon::now()->toDateTimeString();

        $item->status = $request->status;
        $item->style_no = $request->style_no;
        $item->price = $request->price;
        $item->orig_price = $request->orig_price;
        $item->details = $request->description;
        $item->name = $request->item_name;
        $item->made_in_id = $request->made_in;
//        $item->brand_id = $request->brand;
        $item->labeled = $request->labeled;
        $item->memo = $request->memo;
        $item->youtube_url = $request->youtube_url;
        $item->specification = $request->specification;

        $item->save();
        $item->touch();

        if($item->status == 0){
            CartItem::where('item_id', $item->id)->delete();
        }

        $item->itemcategories()->delete();

        $c = 0;

        foreach ($request->d_parent_category as $cat) {

            ItemCategory::create([
                'item_id' => $item->id,
                'default_parent_category' => $cat,
                'default_second_category' => $request->d_second_parent_category[$c],
                'default_third_category' => $request->d_third_parent_category[$c],
            ]);
            $c++;
        }

        if(isset($request->item_value) && count($request->item_value) > 0){
            $valueAttach = [];
            foreach ($request->item_value as $value) {
                $valueAttach[] = $value;
            }
            $item->values()->sync($valueAttach);
        }else{
            $item->values()->detach();
        }

        if($request->specification == 1 || $request->specification == 2){
            $colorAttach = [];
            foreach ($request->colors as $color) {
                $var = 'color_available_'.$color;

                $colorAttach[$color] = [
                    'available' => ($request->$var ? 1 : 0)
                ];
            }
            $item->colors()->sync($colorAttach);
            $item->sizes()->detach();
        }

        if($request->specification == 1 || $request->specification == 3){

            if($request->specification == 1){
                if(isset($request->inv) && count($request->inv) > 0){
                    $sizeAttach = [];
                    foreach ($request->inv as $inv){
                        $sizeAttach[$inv['color_size']] = [
                            'available' => 1
                        ];
                    }
                    $item->sizes()->detach();
                    $item->sizes()->sync($sizeAttach);
                }
            }else{
                $sizeAttach = [];
                foreach ($request->sizes as $size) {
                    $var = 'size_available_'.$size;

                    $sizeAttach[$size] = [
                        'available' => ($request->$var ? 1 : 0)
                    ];
                }
                $item->sizes()->detach();
                $item->sizes()->sync($sizeAttach);
            }
        }

        if($request->specification == 4){
            $item->colors()->detach();
            $item->sizes()->detach();
        }

        $itemInvModel = new ItemInv();
        $itemInvModel->where('item_id', $item->id)->delete();
        $itemInvIds = [];
        if(isset($request->inv) && count($request->inv) > 0){
            foreach ($request->inv as $inv){
                $itemInvModel = new ItemInv();
                $itemInvModel->item_id = $item->id;

                if($request->specification == 1 || $request->specification == 2){
                    $itemInvModel->color_id = $inv['color_id'];
                    $itemInvModel->color_name = $inv['color_name'];
                }

                $itemInvModel->qty = $inv['qty'];

                if($request->specification == 1){
                    $itemInvModel->itemsize = $inv['color_size'];
                }

                if($request->specification == 3){
                    $itemInvModel->itemsize = $inv['size_id'];
                }

                if(isset($inv['sort'])){
                    $itemInvModel->sort = $inv['sort']+1;
                }

                $itemInvModel->available_on = $inv['availability_inv'];
                $itemInvModel->created_at = Carbon::now();
                //dd($itemInvModel);
                $itemInvModel->save();
                $itemInvIds[] = $itemInvModel->id;
            }
        }

        if ($request->imagesId) {
            for ($i = 0; $i < count($request->imagesId); $i++) {
                $image = ItemImages::where('id', $request->imagesId[$i])->first();

                if ($image->compressed_image_path == null) {
                    $paths = optimizedImage($image->image_path);

                    $image->compressed_image_path = $paths['compressed_img_webp'];
                    $image->compressed_image_jpg_path = $paths['compressed_img_jpg'];
                    $image->thumbs_image_path = $paths['thumbs_img'];
                }

                if($request->specification == 1 || $request->specification == 2){
                    $image->color_id = $request->imageColor[$i];
                }

                $image->sort = $i + 1;
                $image->item_id = $item->id;
                $image->save();
            }

            $images = ItemImages::where('item_id', $item->id)
                ->whereNotIn('id', $request->imagesId)
                ->get();

            foreach ($images as $image) {
                deleteImagesFromModel($image);
                $image->delete();
            }
        }

        return redirect()->back()->with('message', 'Item Updated!');
    }

    public function cloneItem(Item $item) {

        $item->load('colors', 'sizes', 'images', 'categories');

        $madeInCountries = MadeInCountry::where('status', 1)->orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $masterColors = MasterColor::orderBy('name')->get();
        $colors = Color::where('status', 1)->orderBy('name')->get();
        $sizes = Size::where('status', 1)->orderBy('name')->get();
        $itemValues = ItemValue::where('status', 1)->orderBy('name')->get();
        $inventorydata = DB::table('item_inv')
            ->where('item_inv.item_id',$item->id)
            ->get();

        // Images color id
        $imagesColorIds = [];
        foreach($item->images as $img)
            $imagesColorIds[] = $img->color_id;

        // Default Categories
        $defaultCategories = [];

        $categoriesCollection = Category::orderBy('sort')->orderBy('name')->get();

        foreach($categoriesCollection as $cc) {
            if ($cc->parent == 0) {
                $data = [
                    'id' => $cc->id,
                    'name' => $cc->name,
                    'slug' => $cc->slug
                ];

                $subCategories = [];
                foreach($categoriesCollection as $cat) {
                    if ($cat->parent == $cc->id) {
                        $data2 = [
                            'id' => $cat->id,
                            'name' => $cat->name,
                            'slug' => $cat->slug
                        ];

                        $data3 = [];
                        foreach($categoriesCollection as $item2) {
                            if ($item2->parent == $cat->id) {
                                $data3[] = [
                                    'id' => $item2->id,
                                    'name' => $item2->name,
                                    'slug' => $item2->slug
                                ];
                            }
                        }

                        $data2['subCategories'] = $data3;
                        $subCategories[] = $data2;
                    }
                }

                $data['subCategories'] = $subCategories;
                $defaultCategories[] = $data;
            }
        }

        return view('admin.dashboard.item_list.clone_item', compact('item', 'madeInCountries', 'brands', 'masterColors', 'colors', 'sizes', 'itemValues', 'inventorydata', 'imagesColorIds', 'defaultCategories'))
            ->with('page_title', 'Create a New Item');
    }

    public function cloneItemPost(Item $old_item, Request $request) {
        Validator::extend('numericarray', function($attribute, $value, $parameters)
        {
            foreach($value as $v) {
                if($v==null) return false;
            }
            return true;
        });

        if($request->specification == 1 || $request->specification == 2){
            $request->validate([
                'style_no' => 'required|max: 255|unique:items,style_no',
                'item_name' => 'required|max: 255',
                'price' => 'required|regex:/^-?[0-9]+(?:\.[0-9]{1,2})?$/',
                'orig_price' => 'nullable|numeric|min:'.$request->price,
                'description' => 'nullable',
                'd_parent_category' => 'required|numericarray',
                'memo' => 'nullable|max:255',
                'colors' => 'required',
                'video' => 'nullable|mimes:mp4',
//                'brand' => 'required'
            ]);
        }else if($request->specification == 3){
            $request->validate([
                'style_no' => 'required|max: 255|unique:items,style_no',
                'item_name' => 'required|max: 255',
                'price' => 'required|regex:/^-?[0-9]+(?:\.[0-9]{1,2})?$/',
                'orig_price' => 'nullable|numeric|min:'.$request->price,
                'description' => 'nullable',
                'd_parent_category' => 'required|numericarray',
                'memo' => 'nullable|max:255',
                'sizes' => 'required',
                'video' => 'nullable|mimes:mp4',
//                'brand' => 'required'
            ]);
        }else{
            $request->validate([
                'style_no' => 'required|max: 255|unique:items,style_no',
                'item_name' => 'required|max: 255',
                'price' => 'required|regex:/^-?[0-9]+(?:\.[0-9]{1,2})?$/',
                'orig_price' => 'nullable|numeric|min:'.$request->price,
                'description' => 'nullable',
                'd_parent_category' => 'required|numericarray',
                'memo' => 'nullable|max:255',
                'video' => 'nullable|mimes:mp4',
//                'brand' => 'required'
            ]);
        }

        // Create slug from categoryname
        $slug = $this->slugGenerator($request->item_name, $request->style_no , $request->d_parent_category);

        if ($request->status == '1'){
            $activated_at = Carbon::now()->toDateTimeString();
        }else{
            $activated_at = null;
        }

        $itemCount = Item::count() + 1;

        $item = Item::create([
            'status' => $request->status,
            'activated_at' => $activated_at,
            'style_no' => $request->style_no,
            'price' => $request->price,
            'orig_price' => $request->orig_price,
            'sorting' => $itemCount,
            'details' => $request->description,
            'name' => $request->item_name,
            'slug' => $slug,
            'made_in_id' => $request->made_in,
//            'brand_id' => $request->brand,
            'labeled' => $request->labeled,
            'memo' => $request->memo,
            'youtube_url' => $request->youtube_url,
            'specification' => $request->specification
        ]);

        $c = 0;
        foreach ($request->d_parent_category as $cat) {
            ItemCategory::create([
                'item_id' => $item->id,
                'default_parent_category' => $cat,
                'default_second_category' => $request->d_second_parent_category[$c],
                'default_third_category' => $request->d_third_parent_category[$c],
            ]);

            $c++;
        }

        if ($request->video) {
            $filename = Uuid::generate()->string;
            $file = $request->file('video');
            $ext = $file->getClientOriginalExtension();

            $destinationPath = '/videos';
            $file->move(public_path($destinationPath), $filename.".".$ext);
            $videoPath = $destinationPath."/".$filename.".".$ext;
            $item->video = $videoPath;
        } else if ($old_item->video != null) {
            $filename = Uuid::generate()->string;
            $destinationPath = '/videos/'.$filename.'.mp4';

            File::Copy(public_path($old_item->video), public_path($destinationPath));

            $item->video = $destinationPath;
        }

        if(isset($request->item_value) && count($request->item_value) > 0){
            $item->values()->detach();
            $item->values()->attach($request->item_value);
        }

        if($request->specification == 1 || $request->specification == 2){
            $colorAttach = [];
            foreach ($request->colors as $color) {
                $var = 'color_available_'.$color;

                $colorAttach[$color] = [
                    'available' => ($request->$var ? 1 : 0)
                ];
            }

            $item->colors()->attach($colorAttach);
            $item->sizes()->detach();
        }


        if($request->specification == 1 || $request->specification == 3){

            if($request->specification == 1){
                if(isset($request->inv) && count($request->inv) > 0){
                    $sizeAttach = [];
                    foreach ($request->inv as $inv){
                        $sizeAttach[$inv['color_size']] = [
                            'available' => 1
                        ];
                    }
                    $item->sizes()->detach();
                    $item->sizes()->attach($sizeAttach);
                }
            }else{
                $sizeAttach = [];
                foreach ($request->sizes as $size) {
                    $var = 'size_available_'.$size;

                    $sizeAttach[$size] = [
                        'available' => ($request->$var ? 1 : 0)
                    ];
                }
                $item->sizes()->detach();
                $item->sizes()->attach($sizeAttach);
            }
        }

        if($request->specification == 4){
            $item->colors()->detach();
            $item->sizes()->detach();
        }

        $itemInvIds = [];
        if(isset($request->inv) && count($request->inv) > 0){
            foreach ($request->inv as $inv){
                $itemInvModel = new ItemInv();
                $itemInvModel->item_id = $item->id;
                if($request->specification == 1 || $request->specification == 2){
                    $itemInvModel->color_id = $inv['color_id'];
                    $itemInvModel->color_name = $inv['color_name'];
                }

                $itemInvModel->qty = $inv['qty'];

                if($request->specification == 1){
                    $itemInvModel->itemsize = $inv['color_size'];
                }

                if($request->specification == 3){
                    $itemInvModel->itemsize = $inv['size_id'];
                }


                if(isset($inv['sort'])){
                    $itemInvModel->sort = $inv['sort']+1;
                }

                $itemInvModel->available_on = $inv['availability_inv'];
                $itemInvModel->created_at = Carbon::now();
                $itemInvModel->save();
                $itemInvIds[] = $itemInvModel->id;
            }
        }
        $itemInvModel = new ItemInv();
        $itemInvModel->where('item_id', $item->id)->whereNotIn('id', $itemInvIds)->delete();

        if ($request->imagesId) {
            for ($i = 0; $i < sizeof($request->imagesId); $i++) {
                $tmp = ItemImages::where('id', $request->imagesId[$i])->first();

                if (is_null($tmp->item_id)) {
                    $paths = optimizedImage($tmp->image_path);

                    $tmp->item_id = $item->id;
                    $tmp->sort = $i + 1;
                    $tmp->compressed_image_path = $paths['compressed_img_webp'];
                    $tmp->compressed_image_jpg_path = $paths['compressed_img_jpg'];
                    $tmp->thumbs_image_path = $paths['thumbs_img'];
                    $tmp->save();
                } else {
                    $newImagePath = cloneImageFromModel($tmp);
                    //$tempImagePath = temporaryImageClone($tmp->image_path);
                    $paths = optimizedImage($newImagePath);
                    //$allPaths = imageMove($tempImagePath, $folderName = 'items', $imageStoreTypes = ['image_path', 'resize_compressed', 'resize_thumbs_image_path'], 1500, 1500);

                    if($request->specification == 1 || $request->specification == 2){
                        ItemImages::create([
                            'item_id' => $item->id,
                            'sort' => $i+1,
                            'color_id' => $request->imageColor[$i],
                            'image_path' => $newImagePath,
                            'compressed_image_path' => $paths['compressed_img_webp'],
                            'compressed_image_jpg_path' => $paths['compressed_img_jpg'],
                            'thumbs_image_path' => $paths['thumbs_img']
                        ]);
                    }else{
                        ItemImages::create([
                            'item_id' => $item->id,
                            'sort' => $i+1,
                            'image_path' => $newImagePath,
                            'compressed_image_path' => $paths['compressed_img_webp'],
                            'compressed_image_jpg_path' => $paths['compressed_img_jpg'],
                            'thumbs_image_path' => $paths['thumbs_img']
                        ]);
                    }
                }

            }
        }

        $item->save();

        return redirect()->route('admin_item_list_all');
    }

    public function cloneMultiItems(Request $request) {

        $items = Item::select('id','style_no')
            ->whereIn('id', $request->ids)
            ->orderBy('updated_at', 'desc')
            ->orderBy('id','desc')
            ->get();

        if(count($items) > 0){
            $items = array_reverse($items->toArray());
        }

        foreach ($items as $itemD) {
            $item = Item::where('id', $itemD['id'])
                ->with('itemcategory','images', 'colors', 'sizes')->get()->first();

            $new = $item->replicate();

            $itemCount = Item::count() + 1;

            $new->sorting = $itemCount;

            $videoPath = null;
            if ($new->video) {
                $filename = Uuid::generate()->string;
                $arr = explode('.',$new->video);
                $ext = end($arr);

                $destinationPath = 'videos';

                File::copy(public_path($new->video), public_path($destinationPath."/".$filename.".".$ext));
                $videoPath = $destinationPath."/".$filename.".".$ext;
            }

            // Create slug from categoryname
            $cat_ids = ItemCategory::where('item_id', $itemD['id'])->pluck('default_parent_category')->toArray();

            $slug = $this->slugGenerator($item->item_name, $item->style_no, $cat_ids);

            if ($new->status == '1'){
                $activated_at = Carbon::now()->toDateTimeString();
            }else{
                $activated_at = null;
            }

            $cCheck = Item::select('id','style_no')->where('style_no','Like', '%'.$item->style_no.'.%')->orderBy('id', 'desc')->take(1)->get()->toArray();
            if(count($cCheck) > 0){
                $cCheck = $cCheck[0];
                $new->style_no = $cCheck['style_no'].'.';
            } else {
                $new->style_no .= '.';
            }

            $new->slug = $slug;
            $new->video = $videoPath;
            $new->activated_at = $activated_at;
            $new->updated_at = Carbon::now();
            $new->save();
            $newItemId = $new->id;

            foreach($item->itemcategory as $category){

                $tmp = $category->replicate();

                try{
                    ItemCategory::create([
                        'item_id' => $new->id,
                        'default_parent_category' => $tmp->default_parent_category,
                        'default_second_category' => $tmp->default_second_category,
                        'default_third_category' => $tmp->default_third_category,
                    ]);
                }catch (\Exception $exception) {

                }
            }

            //Item Values
            $new->values()->attach($item->values);

            // Colors
            if($new->specification == 1 || $new->specification == 2){
                $colorIds = $item->colors->pluck('id')->toArray();
                $new->colors()->attach($colorIds);
            }

            // Sizes
            if($new->specification == 1 || $new->specification == 3){
                $sizeIds = $item->sizes->pluck('id')->toArray();
                $new->sizes()->attach($sizeIds);
            }

            $itemInvModel = new ItemInv();
            $itemInv = $itemInvModel->where('item_id', $item->id)->get()->toArray();
            if(count($itemInv) > 0){
                foreach ($itemInv as $inv){
                    $itemInvModel = new ItemInv();
                    $itemInvModel->item_id = $newItemId;

                    if($new->specification == 1 || $new->specification == 2){
                        $itemInvModel->color_id = $inv['color_id'];
                        $itemInvModel->color_name = $inv['color_name'];
                    }

                    if($request->specification == 1){
                        $itemInvModel->itemsize = $inv['color_size'];
                    }

                    if($request->specification == 3){
                        $itemInvModel->itemsize = $inv['size_id'];
                    }


                    if(isset($inv['sort'])){
                        $itemInvModel->sort = $inv['sort']+1;
                    }
                    $itemInvModel->qty = $inv['qty'];

                    if(!empty($inv['sort'])){
                        $itemInvModel->sort = $inv['sort'] + 1;
                    }

                    $itemInvModel->available_on = $inv['available_on'];
                    $itemInvModel->created_at = Carbon::now();
                    $itemInvModel->save();
                }
            }

            // images
            foreach ($item->images as $image) {
                $tmp = $image->replicate();

                try {
                    $tempImagePath = temporaryImageClone($tmp->image_path);
                    $allPaths = imageMove($tempImagePath, $folderName = 'items', $imageStoreTypes = ['image_path', 'resize_compressed', 'resize_thumbs_image_path'], 900, 900);

                    $tmp->item_id = $new->id;
                    $tmp->image_path = $allPaths['image_path'];
                    $tmp->compressed_image_path = $allPaths['resize_compressed'];
                    $tmp->thumbs_image_path = $allPaths['resize_thumbs_image_path'];
                    $tmp->save();
                } catch (\Exception $exception) {

                }
            }
        }
    }

    public function itemListByCategory(Category $category, Request $request) {

        $catLvl = 1;
        $ids = [];
        $itemsids = ItemCategory::where('default_parent_category',$category->id)->orwhere('default_second_category',$category->id)->orwhere('default_third_category',$category->id)->get();
        foreach($itemsids as $catitem){
            $ids[] = $catitem->item_id;
        }

        // Active Items
        $activeItemsQuery = Item::query();
        $activeItemsQuery->whereIn('id', $ids);
        $activeItemsQuery->where('status', 1)->with('category', 'images');

        // Active Items
        $activeItemsQuery = Item::query();
        $activeItemsQuery->whereIn('id', $ids);
        $activeItemsQuery->where('status', 1)->with('category', 'images');

        //Active Item Search
        if ($request->text){
            $data = explode(',', $request->text);
            $activeItemsQuery->where(function ( $q) use ($data, $request){
                if (isset($request->style) && $request->style == '1') {
                    foreach ($data as $value){
                        $q->orWhere('style_no', 'like', '%' . ltrim($value, ' ') . '%');
                    }
                }

                if (isset($request->des) && $request->des == '1') {
                    $q->orWhere('description', 'like', '%' . $request->text . '%');
                }

                if (isset($request->name) && $request->name == '1') {
                    $q->orWhere('name', 'like', '%' . $request->text . '%');
                }
            });
        }

        //Active Item Sort
        if (isset($request->s1) && $request->s1 != '') {
            if ($request->s1 == '4')
                $activeItemsQuery->orderBy('price');
            else if ($request->s1 == '1')
                $activeItemsQuery->orderBy('updated_at', 'desc');
            else if ($request->s1 == '2')
                $activeItemsQuery->orderBy('created_at', 'desc');
            else if ($request->s1 == '3')
                $activeItemsQuery->orderBy('activated_at', 'desc');
            else if ($request->s1 == '5')
                $activeItemsQuery->orderBy('price', 'desc');
            else if ($request->s1 == '6')
                $activeItemsQuery->orderBy('style_no');
            else if ($request->s1 == '0') {
                $activeItemsQuery->orderBy('sorting', 'desc');
                $activeItemsQuery->orderBy('activated_at', 'desc');
            }
        } else {
            $activeItemsQuery->orderBy('sorting', 'desc');
            $activeItemsQuery->orderBy('activated_at', 'desc');
        }

        // History
        DB::table('item_list_history')->delete();
        $historyData = [];
        $q = clone $activeItemsQuery;
        $q->select('id')->orderBy('id', 'asc');
        $tmp = $q->pluck('id')->toArray();

        foreach ($tmp as $t)
            $historyData[] = [
                'item_id' => $t,
                'status' => 1
            ];
        $totalActiveitem = $activeItemsQuery->count();
        $activeItems = $activeItemsQuery->paginate(50, ['*'], 'p1');

        // Inactive Items
        $inactiveItemsQuery = Item::query();
        $inactiveItemsQuery->whereIn('id', $ids);

        $inactiveItemsQuery->where('status', 0)->with('category', 'images');

        //Inactive Item Search
        if ($request->text){
            $data = explode(',', $request->text);
            $inactiveItemsQuery->where(function ( $q) use ($data, $request){
                if (isset($request->style) && $request->style == '1') {
                    foreach ($data as $value){
                        $q->orWhere('style_no', 'like', '%' . ltrim($value, ' ') . '%');
                    }
                }

                if (isset($request->des) && $request->des == '1') {
                    $q->orWhere('description', 'like', '%' . $request->text . '%');
                }

                if (isset($request->name) && $request->name == '1') {
                    $q->orWhere('name', 'like', '%' . $request->text . '%');
                }
            });
        }

        //Inactive Item Sort
        if (isset($request->s2) && $request->s2 != '') {
            if ($request->s2 == '4')
                $inactiveItemsQuery->orderBy('price');
            else if ($request->s2 == '1')
                $inactiveItemsQuery->orderBy('updated_at', 'desc');
            else if ($request->s2 == '2')
                $inactiveItemsQuery->orderBy('created_at', 'desc');
            else if ($request->s2 == '3')
                $inactiveItemsQuery->orderBy('activated_at', 'desc');
            else if ($request->s2 == '5')
                $inactiveItemsQuery->orderBy('price', 'desc');
            else if ($request->s2 == '6')
                $inactiveItemsQuery->orderBy('style_no');
            else if ($request->s2 == '0')
                $inactiveItemsQuery->orderBy('sorting', 'desc');
        } else {
            $inactiveItemsQuery->orderBy('created_at', 'desc');
            // $inactiveItemsQuery->orderBy('sorting', 'desc');
        }

        // History
        $q = clone $inactiveItemsQuery;
        $q->select('id');
        $tmp = $q->pluck('id')->toArray();

        foreach ($tmp as $t)
            $historyData[] = [
                'item_id' => $t,
                'status' => 0
            ];

        DB::table('item_list_history')->insert($historyData);
        $totalInactiveitem = $inactiveItemsQuery->count();
        $inactiveItems = $inactiveItemsQuery->paginate(50, ['*'], 'p2');

        $appends = [
            'p1' => $activeItems->currentPage(),
            'p2' => $inactiveItems->currentPage(),
        ];

        $categories = Category::where('parent', 0)->orderBy('sort')->orderBy('name')->get();

        // Url history
        DB::table('item_list_url_history')->delete();
        DB::table('item_list_url_history')->insert([
            'url' => url()->full()
        ]);


        return view('admin.dashboard.item_list.index', compact('category','categories', 'activeItems', 'inactiveItems','totalInactiveitem','totalActiveitem',
            'appends'))->with('page_title', $category->name);
    }

    public function dataImportView() {
        return view('admin.dashboard.data_import')->with('page_title', 'Data Import');
    }

    public function dataImportReadFile(Request $request) {
        $file = $request->file('file');
        $ext = $file->getClientOriginalExtension();

        if (in_array($ext, ['xlsx', 'csv', 'xls'])) {
            $excel = Excel::load($file->getRealPath(), function ($reader) {
                $content = $reader->get();
            });

            $data = $excel->get()->toArray();

            if (sizeof($data) == 0)
                return redirect()->back()->with('error', 'Invalid file');

            $item = $data[0];
            $items = [];

            if (sizeof($item) >= 2 && sizeof($item) <= 3 && array_key_exists("styleno", $item) && array_key_exists("images", $item)) {
                foreach ($data as $item)
                    $items [] = $item;

                return view('admin-v2.dashboard.image_import_stats', compact('items'));
            }

            if (!array_key_exists("styleno", $item) ||
                !array_key_exists("itemname", $item) ||
                !array_key_exists("defaultcategory", $item) ||
                !array_key_exists("size", $item) ||
                !array_key_exists("pack", $item) ||
                !array_key_exists("packqty", $item) ||
                !array_key_exists("unitprice", $item) ||
                !array_key_exists("originalprice", $item) ||
                !array_key_exists("availableon", $item) ||
                !array_key_exists("productdescription", $item) ||
                !array_key_exists("fabric", $item) ||
                !array_key_exists("color", $item)) {

                return redirect()->back()->with('error', 'Invalid file');
            }

            foreach ($data as &$item) {
                if ($item['styleno'] != null || $item['styleno'] != '') {
                    // Available On
                    $date = '';

                    if ($item['availableon'] != null || $item['availableon'] != '') {
                        try {
                            $date = $item['availableon']->format('Y-m-d');
                        } catch (\Throwable $e) {

                        }
                        $item['availableon'] = $date;
                    }

                    $items[] = $item;
                }
            }

            return view('admin.dashboard.data_import_stats', compact('items'));
        } else {
            return redirect()->back()->with('error', 'Invalid file');
        }
    }

    public function dataImportUpload(Request $request) {
        // Style no check
        $found = false;

        $item = Item::where('style_no', $request->styleno)->first();

        if ($item) {
            $found = true;
        }

        // Default Category Check
        if ($request->defaultcategory != null && $request->defaultcategory == '')
            return response()->json(['success' => false, 'message' => 'Default category required.']);

        $dc = explode(',', $request->defaultcategory);
        $defaultCategory = Category::where('name', $dc[0])
            ->where('parent', 0)
            ->first();

        if (!$defaultCategory)
            return response()->json(['success' => false, 'message' => 'Default category not found.']);

        // Second default category check
        $defaultCategorySecondId = null;
        if (sizeof($dc) > 1) {
            $defaultCategorySecond = Category::where('name', $dc[1])
                ->where('parent', $defaultCategory->id)
                ->first();

            if (!$defaultCategorySecond)
                return response()->json(['success' => false, 'message' => 'Invalid Default Sub category.']);
            else
                $defaultCategorySecondId = $defaultCategorySecond->id;
        }

        // Third default category check
        $defaultCategoryThirdId = null;
        if (sizeof($dc) > 2) {
            $defaultCategoryThird = Category::where('name', $dc[2])
                ->where('parent', $defaultCategorySecond->id)
                ->first();

            if (!$defaultCategoryThird)
                return response()->json(['success' => false, 'message' => 'Invalid Default Sub category.']);
            else
                $defaultCategoryThirdId = $defaultCategoryThird->id;
        }

        // Size Check
        if ($request->size != null && $request->size == '')
            return response()->json(['success' => false, 'message' => 'Size is required.']);

        $pack = Pack::where('status', 1)
            ->where('name', $request->size)
            ->first();

        if (!$pack)
            return response()->json(['success' => false, 'message' => 'Size not found.']);


        // Made In Country
        $madeInId = null;

        if ($request->madein != null && $request->madein != '') {
            $madeIn = MadeInCountry::where('status', 1)
                ->where('name', $request->madein)
                ->first();

            if ($madeIn)
                $madeInId = $madeIn->id;
        }

        // Availability
        $availability = Availability::$IN_STOCK;

        if ($request->availableon != null) {
            if(time() < strtotime($request->availableon)) {
                $availability = Availability::$ARRIVES_SOON;
            }
        }

        // Colors check
        if ($request->color != null && $request->color == '')
            return response()->json(['success' => false, 'message' => 'Color is required.']);

        $colorIds = [];
        $colors = explode(',', $request->color);

        foreach ($colors as $color) {
            $c = Color::where('status', 1)
                ->where('name', $color)
                ->first();

            if (!$c) {
                $c = Color::create([
                    'name' => $color,
                    'status' => 1,
                ]);
            }

            $colorIds[] = $c->id;
        }

        if (sizeof($colorIds) == 0)
            return response()->json(['success' => false, 'message' => 'Color(s) not found.']);

        // Create Item
        if ($found) {
            $item->price = $request->unitprice;
            $item->orig_price = $request->originalprice;
            $item->pack_id = $pack->id;
            $item->description = $request->productdescription;
            $item->available_on = $request->availableon;
            $item->availability = $availability;
            $item->name = $request->itemname;
            $item->default_parent_category = $defaultCategory->id;
            $item->default_second_category = $defaultCategorySecondId;
            $item->default_third_category = $defaultCategoryThirdId;
            $item->min_qty = $request->packqty;
            $item->fabric = $request->fabric;
            $item->made_in_id = $madeInId;
            $item->memo = $request->inhousememo;

            $item->save();
            $item->touch();

            $item->colors()->detach();
            foreach ($item->images as $image)
                $image->delete();
        } else {
            $item = Item::create([
                'status' => 0,
                'style_no' => $request->styleno,
                'price' => $request->unitprice,
                'orig_price' => $request->originalprice,
                'pack_id' => $pack->id,
                'description' => $request->productdescription,
                'available_on' => $request->availableon,
                'availability' => $availability,
                'name' => $request->itemname,
                'default_parent_category' => $defaultCategory->id,
                'default_second_category' => $defaultCategorySecondId,
                'default_third_category' => $defaultCategoryThirdId,
                'min_qty' => $request->packqty,
                'fabric' => $request->fabric,
                'made_in_id' => $madeInId,
                'memo' => $request->inhousememo,
            ]);
        }

        $item->colors()->attach($colorIds);

        // Images
        if ($request->images != '') {
            $images_color = [];
            $urls = explode(',', $request->images);
            $colors = explode(',', $request->color);

            if ($request->images_color && $request->images_color != '')
                $images_color = explode(',', $request->images_color);

            $sort = 1;
            foreach ($urls as $url) {
                $tempImagePath = temporaryImageUploadFromUrl($url);
                $allPaths = imageMove($tempImagePath, $folderName = 'items', $imageStoreTypes = ['image_path', 'compressed_image_path', 'thumbs_image_path']);

                // Color
                $colorId = null;

                if (isset($colors[$sort-1])) {
                    $colorName = $colors[$sort - 1];

                    $color = Color::where('status', 1)
                        ->where('name', $colorName)
                        ->first();

                    if ($color)
                        $colorId = $color->id;
                }

                ItemImages::create([
                    'item_id' => $item->id,
                    'sort' => $sort,
                    'color_id' => $colorId,
                    'image_path' => $allPaths['image_path'],
                    'thumbs_image_path' => $allPaths['thumbs_image_path'],
                    'compressed_image_path' => $allPaths['compressed_image_path'],
                ]);

                $sort++;
            }
        }

        return response()->json(['success' => true, 'message' => 'Completed']);
    }

    public function dataImportImage(Request $request) {
        $item = Item::where('style_no', $request->styleno)->first();

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Style No. not found.']);
        }

        foreach ($item->images as $image)
            $image->delete();

        // Images
        if ($request->images != '') {
            $urls = explode(',', $request->images);

            $sort = 1;
            foreach ($urls as $url) {
                $tempImagePath = temporaryImageUploadFromUrl($url);
                $allPaths = imageMove($tempImagePath, $folderName = 'items', $imageStoreTypes = ['image_path', 'compressed_image_path', 'thumbs_image_path']);

                ItemImages::create([
                    'item_id' => $item->id,
                    'sort' => $sort,
                    'image_path' => $allPaths['image_path'],
                    'compressed_image_path' => $allPaths['compressed_image_path'],
                    'thumbs_image_path' => $allPaths['thumbs_image_path'],
                ]);

                $sort++;
            }
        }

        return response()->json(['success' => true, 'message' => 'Completed']);
    }

    public function slugGenerator($name, $style, $category, $itemId= null)
    {
        $set = Category::Where('slug', 'sets')->first();
        if($set && in_array($set->id , $category)){
            $itemName = $name."-gelpolish-set-".$style;
        }else{
            $itemName = $name."-gelpolish-".$style;
        }
        $url = str_replace('/', '-', str_replace(' ', '-', str_replace('&', '', str_replace('?', '', str_replace('#', '', str_replace('%', '', str_replace('*', '', str_replace('$', '', strtolower($itemName)))))))));
        $url = str_replace('--', '-', $url);
        if($itemId){
            $slugCheck = Item::where('slug', $url)->where('id', '!=', $itemId)->count();
        }else{
            $slugCheck = Item::where('slug', $url)->count();
        }
        if ($slugCheck != 0) {
            $url .= '-'.$slugCheck;
        }

        return $url;
    }
}
