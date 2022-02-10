<?php

namespace App\Http\Controllers\Api;

use App\Model\CategoryColor;
use App\Model\Meta;
use App\Enumeration\Role;
use App\Model\Category;
use App\Model\Item;
use App\Model\ItemCategory;
use App\Model\ItemImages;
use App\Model\Setting;
use App\Model\MadeInCountry;
use App\Model\WishListItem;
use App\Model\User;
use App\Model\ItemInv;
use App\Model\State;
use App\Model\Country;
use App\Model\AdminShipMethod;
use Illuminate\Http\Request;
use App\Model\Color;
use App\Model\Size;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Intervention\Image\Exception\NotReadableException;
use App\Enumeration\PageEnumeration;
use Uuid;
use Image;
use File;
use ImageOptimizer;
use App\Enumeration\Availability;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index() {

        $itemsQuery = Item::query();
        $itemsQuery->where('status', 1)->with('categories', 'images');

        $itemsQuery->orderBy('sorting', 'desc');

        $items = $itemsQuery->paginate(40);

        return response()->json(['items_count' => count($items), 'items' => $items], 200);

    }

    public function searchItems(Request $request, $parent = null, $second = null, $third = null) {
        $query = Item::query();
        $query->where('status', 1);
        $query->orderBy('style_no', 'asc');
        $category = null;
        $currentCategory = null;
        $meta = null;
        if(!$request->search_text) {
            $parentCategory = Category::where('slug', $parent)->first();
            if (!$parentCategory)
                return response()->json(['notfound' => '404'], 200);
            if ($third) {
                $category = Category::where('slug', $third)->first();
                if (!$category)
                    return response()->json(['notfound' => '404'], 200);
            } else if ($second) {
                $category = Category::where('slug', $second)->first();
                if (!$category)
                    return response()->json(['notfound' => '404'], 200);
            } else {
                $category = Category::where('slug', $parent)->first();
                if (!$category)
                    return response()->json(['notfound' => '404'], 200);
            }

            $currentCategory['banners'] = CategoryColor::where('category_id', $parentCategory->id)->get();
            $currentCategory['parent'] = $parentCategory;
//            $currentCategory['current'] = $category;
            $currentCategory['sub_category'] = $second ? Category::where('slug', $second)->first() : null;

            $meta = Meta::where('category', $category->id)->first();
            $metas = [
                'title' => $meta ? $meta->title : null,
                'description' => $meta ? $meta->description : null,
                'image' => null,
            ];
        }else{
            $metas = [
                'title' => $request->search_text,
                'description' => $request->search_text,
                'image' => null,
            ];
        }

        if ($request->parent && $request->parent !== "" && !$request->search_text) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->whereHas('parent_category', function ($q2) use ($request) {
                    $q2->where('slug', $request->parent);
                });
            });
        }

        if ($request->second && $request->second !== "" && !$request->search_text) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->whereHas('second_category', function ($q2) use ($request) {
                    $q2->where('slug', $request->second);
                });
            });
        }
        if ($request->third && $request->third !== "" && !$request->search_text) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->whereHas('third_category', function ($q2) use ($request) {
                    $q2->where('slug', $request->third);
                });
            });
        }

        if ($request->colors && count($request->colors)) {
            $query->whereHas('colors', function($q) use ($request) {
                $q->whereIn('colors.id', $request->colors);
            });
        }

        if ($request->designers && count($request->designers)) {
            $query->whereHas('brand', function($q) use ($request) {
                $q->whereIn('brands.id', $request->designers);
            });
        }

        if ($request->values && count($request->values)) {
            $query->whereHas('values', function($q) use ($request) {
                $q->whereIn('item_values.id', $request->values);
            });
        }

        if ($request->search_text && $request->search_text !== "") {
            $query->where(function($q) use ($request) {
                $q->where('style_no', 'like', '%'.$request->search_text.'%');
                $q->orWhere('details', 'like', '%'.$request->search_text.'%');
                $q->orWhere('name', 'like', '%'.$request->search_text.'%');
            });
        }


        if (isset($request->startPrice) && isset($request->endPrice)) {
            $startPrice = explode(',', $request->startPrice);
            $endPrice = explode(',', $request->endPrice);


            $query->where(function($q) use ($startPrice, $endPrice) {
                for($i=0; $i < count($startPrice); $i++) {
                    $q->orWhere(function ($q2) use ($startPrice, $endPrice, $i) {
                        $q2->Where('price', '>=', (float) $startPrice[$i])->where('price', '<=', (float) $endPrice[$i]);
                    });
                }
            });
        }
        $query->orderBy('sorting', 'desc');
        $default_img = Setting::where('name', 'default-item-image')->first();
        $items = $query->with('categories', 'images','brand', 'values', 'colors')->paginate($request->PerPage);
        $i=0;
        foreach($items as $item){
            if($i==0){
                foreach($item->images as $image){
                    if(!empty($image->compressed_image_path)){
                        $metas['image'] = asset($image->compressed_image_path);
                        break;
                    }
                }
            }
            $items[$i]['sizes'] =  \DB::table('item_size')
                ->join('sizes','sizes.id','item_size.size_id')
                ->where('item_size.item_id',$item->id)
                ->get();

            $item['colors'] = $item->colors;
            $i++;
        }
        $currentCategory['metas'] = $metas;
        return response()->json(['items_count' => $query->count(), 'items' => $items, 'category' => $category, 'currentCategory'=>$currentCategory, 'default_img' =>$default_img], 200);
    }

    public function searchInSite(Request $request) {
        $request->validate([
            'search_text' => 'required|string|min:3',
        ]);
        $query = Item::query();
        $query->where('status', 1);
        $query->orderBy('style_no', 'asc');

        $query->where(function($q) use ($request) {
            $q->where('style_no', 'like', '%'.$request->search_text.'%');
            $q->orWhere('details', 'like', '%'.$request->search_text.'%');
            $q->orWhere('name', 'like', '%'.$request->search_text.'%');
        });

        $query->orderBy('sorting', 'desc');
        $items = $query->with('images', 'brand', 'colors')->take(8)->get();

        $categoriesQuery = Category::query();
        $categoriesQuery->where('status', 1);
        $categoriesQuery->where(function ( $q) use ($request){
            $q->orWhere('name', 'like', '%' . $request->search_text . '%');
            $q->orWhere('slug', 'like', '%' . $request->search_text . '%');
        });
        $categories = $categoriesQuery->take(10)->get();

        return response()->json(['items' => $items, 'categories' => $categories ], 200);
    }

    public function GetAllSizes(){
        $sizes = Size::all();
        return response()->json(['sizes' => $sizes], 200);
    }

    public function GetAllColors(){
        $colorsall = Color::query();
        $colorsall->join('color_item', 'colors.id', '=', 'color_item.color_id')->groupby('colors.id');
        $colors = $colorsall->get();
        return response()->json(['colors' => $colors], 200);
    }
    public function getAllCountry(){
        $countries = Country::orderBy('name')->get();
        return response()->json([ 'countries' => $countries ], 200);
    }
    public function getAllStates(){
        $usStates = State::where('country_id', 1)->orderBy('name')->get()->toArray();
        $caStates = State::where('country_id', 2)->orderBy('name')->get()->toArray();
        $states = State::orderBy('name')->get()->toArray();
        return response()->json([ 'caStates' => $caStates, 'usStates' => $usStates, 'states' => $states], 200);
    }
    public function getAllShippingMethods(){
        $shippingMethods = AdminShipMethod::with('courier')->get();
        return response()->json([ 'shippingMethods' => $shippingMethods], 200);
    }

    public function getimages(Request $request){

        $itemImages[0] =   ItemImages::where('item_id',$request->itemId)->orderBy('sort')->first();

        if($request->color > 0){

            $itemImages =   ItemImages::where('item_id',$request->itemId)->where('color_id',$request->color)->orderBy('sort')->get();

            if(count($itemImages) == 0) {

                $itemImages[0] =   ItemImages::where('item_id',$request->itemId)->orderBy('sort')->first();

            }

        }

        return response()->json([  'images' => $itemImages ], 200);
    }


    // Pim
    public function createItem(Request $request) {

        if (!isset($request->username) || !isset($request->password))
            return response()->json(['success' => false, 'message' => 'username & password parameter required.']);

        $user = User::where('user_name', $request->username)
            ->whereIn('role', [Role::$ADMIN, Role::$EMPLOYEE])
            ->with('vendor')->first();

        if (!$user)
            return response()->json(['success' => false, 'message' => 'Username not found.']);

        if ($user->vendor->active == 0)
            return response()->json(['success' => false, 'message' => 'Vendor is inactivate.']);

        if ($user->vendor->verified == 0)
            return response()->json(['success' => false, 'message' => 'Vendor is not verified.']);

        if (Hash::check($request->password, $user->password)) {
            $requiredParameters = ['styleno', 'defaultcategory'];

            foreach ($requiredParameters as $parameter) {
                if (!isset($request->$parameter) || $request->$parameter == '')
                    return response()->json(['success' => false, 'message' => 'These parameters required: '.implode(',', $requiredParameters)]);
            }

            $found = false;
            $item = Item::where('style_no', $request->styleno)
                ->first();

            if ($item) {
                $found = true;
            }



            // Default Category Check
            $dc = explode(',', $request->defaultcategory);

            $defaultCategory = Category::where('id', $dc[0])
                ->where('parent', 0)
                ->first();

            if (!$defaultCategory)
                return response()->json(['success' => false, 'message' => 'Default category not found.']);

            // Second default category check
            $defaultCategorySecondId = null;
            if (sizeof($dc) > 1) {
                $defaultCategorySecond = Category::where('id', $dc[1])
                    ->where('parent', $defaultCategory->id)
                    ->first();

                if (!$defaultCategorySecond)
                    return response()->json(['success' => false, 'message' => 'Sub category not found.']);
                else
                    $defaultCategorySecondId = $defaultCategorySecond->id;
            }

            // Third default category check
            $defaultCategoryThirdId = null;
            if (sizeof($dc) > 2) {
                $defaultCategoryThird = Category::where('id', $dc[2])
                    ->where('parent', $defaultCategorySecond->id)
                    ->first();

                if (!$defaultCategoryThird)
                    return response()->json(['success' => false, 'message' => 'Sub category not found.']);
                else
                    $defaultCategoryThirdId = $defaultCategoryThird->id;
            }

            // Made In Country
            $madeInId = null;

            if ($request->madein != null && $request->madein != '') {
                $madeIn = MadeInCountry::where('status', 1)
                    ->where('name', $request->madein)
                    ->first();

                if ($madeIn)
                    $madeInId = $madeIn->id;
            }

            // Available On
            $date = '';

            if ($request->availableon != null || $request->availableon != '') {
                if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$request->availableon))

                    $date = $request->availableon;
            }

            // Availability
            $availability = Availability::$IN_STOCK;

            if ($date != '') {
                if(time() < strtotime($date)) {
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

            //check activation date
            $time = Carbon::now();
            if($request->status == 1){
                $activation_date = $time->toDateTimeString();
            }else{
                $activation_date = null;
            }

            // Create Item
            if ($found) {
                $item->status = $request->status;
                $item->price = $request->unitprice;
                $item->orig_price = $request->originalprice;
                $item->details = $request->productdescription;
                $item->name = $request->itemname;
                $item->labeled = $request->labeled;
                $item->made_in_id = $madeInId;
                $item->memo = $request->inhousememo;
                $item->activated_at = $activation_date;
                $item->specification = $request->inventory_color_id != '' ? 2 : 4;
                $item->save();
                $item->touch();

                $item->colors()->detach();
                $item->images()->delete();

            } else {
                // Create slug from categoryname
                $slug = $this->slugGenerator($request->itemname, $request->styleno , $dc);
                $itemCount = Item::count() + 1;
                $item = Item::create([
                    'status' => $request->status,
                    'style_no' => $request->styleno,
                    'price' =>$request->unitprice,
                    'orig_price' => $request->originalprice,
                    'details' => $request->productdescription,
                    'sorting' => $itemCount,
                    'slug' => $slug,
                    'specification'=> $request->inventory_color_id != '' ? 2 : 4,
                    'name' => $request->itemname,
                    'labeled' => $request->labeled,
                    'made_in_id' => $madeInId,
                    'memo' => $request->inhousememo,
                    'activated_at' => $activation_date
                ]);

                ItemCategory::create([
                    'item_id' => $item->id,
                    'default_parent_category' => $defaultCategory ? $defaultCategory->id : NULL,
                    'default_second_category' => $defaultCategorySecondId ? $defaultCategorySecondId : NULL,
                    'default_third_category' => $defaultCategoryThirdId ? $defaultCategoryThirdId : NULL,
                ]);
            }


            $item->colors()->attach($colorIds);

            // Images
            if ($request->images != '') {
                $urls = explode(',', $request->images);

                $images_color = [];
                $colors = explode(',', $request->color);

                if ($request->images_color != '')
                    $images_color = explode(',', $request->images_color);

                $sort = 1;
                foreach ($urls as $url) {
                    $convertImage = [];
                    try
                    {
                        $convertImage = $this->optimizedImage($url);
                    }
                    catch(NotReadableException $e)
                    {
                        continue;
                    }

                    // Color
                    $colorId = null;
                    if (sizeof($images_color) >= $sort) {
                        if (in_array($images_color[$sort-1], $colors)) {
                            if ($images_color[$sort-1] != null && $images_color[$sort-1] != '') {
                                $color = Color::where('status', 1)
                                    ->where('name', $images_color[$sort-1])
                                    ->first();

                                if ($color)
                                    $colorId = $color->id;
                            }
                        }
                    }

                    ItemImages::create([
                        'item_id' => $item->id,
                        'sort' => $sort,
                        'color_id' => $colorId,
                        'image_path' => $convertImage['original'],
                        'compressed_image_path' => $convertImage['compressed_img_webp'],
                        'compressed_image_jpg_path' => $convertImage['compressed_img_jpg'],
                        'thumbs_image_path' => $convertImage['thumbs_img'],
                    ]);

                    $sort++;
                }
            }

            //Inventory
            $itemId =  $item->id;
            $itemInvIds = [];
            if ($request->inventory_color_id != '') {
                $inventory_color_id = explode(',', $request->inventory_color_id);
                $inventory_color_name = explode(',', $request->inventory_color_name);
                $inventory_qty = explode(',', $request->inventory_color_qty);
                $inventory_threshold = explode(',', $request->inventory_color_threshold);
                $inventory_available_on = explode(',', $request->inventory_available_on);

                for ($i = 0; $i < sizeof($inventory_color_id);)
                {
                    $itemInvModel = new ItemInv();
                    $itemInvModel->item_id = $itemId;
                    $itemInvModel->color_id = $colorIds[$i];
                    $itemInvModel->color_name = $inventory_color_name[$i];
                    $itemInvModel->qty = $inventory_qty[$i];
                    $itemInvModel->threshold = $inventory_threshold[$i];
                    $itemInvModel->available_on = $inventory_available_on[$i] == '' ? NULL : $inventory_available_on[$i];
                    $itemInvModel->created_at = Carbon::now();
                    $itemInvModel->save();
                    $i++;
                    $itemInvIds[] = $itemInvModel->id;
                }
                $itemInvModel = new ItemInv();
                $itemInvModel->where('item_id', $item->id)->whereNotIn('id', $itemInvIds)->delete();
            }

            if($found){
                return response()->json(['success' => true, 'found' => true, 'message' => 'Item updated successfully.']);
            }else{
                return response()->json(['success' => true, 'found' => false, 'message' => 'Item added successfully.']);
            }
        }

        return response()->json(['success' => false, 'message' => 'Invalid Password.']);
    }

    public function statusChange(Request $request) {
        if (!isset($request->username) || !isset($request->password))
            return response()->json(['success' => false, 'message' => 'username & password parameter required.']);

        $user = User::where('user_id', $request->username)
            ->whereIn('role', [Role::$ADMIN, Role::$EMPLOYEE])
            ->first();

        if (!$user)
            return response()->json(['success' => false, 'message' => 'Username not found.']);

        if ($user->vendor->active == 0)
            return response()->json(['success' => false, 'message' => 'Vendor is inactivate.']);

        if ($user->vendor->verified == 0)
            return response()->json(['success' => false, 'message' => 'Vendor is not verified.']);

        if (Hash::check($request->password, $user->password)) {
            $requiredParameters = ['styleno', 'status'];

            foreach ($requiredParameters as $parameter) {
                if (!isset($request->$parameter) || $request->$parameter == '')
                    return response()->json(['success' => false, 'message' => 'These parameters required: '.implode(',', $requiredParameters)]);
            }

            if ($request->status != '0' && $request->status != '1')
                return response()->json(['success' => false, 'message' => 'Status should be 0 or 1']);

            $item = Item::where('style_no', $request->styleno)->first();

            if (!$item)
                return response()->json(['success' => false, 'message' => 'Item not found']);

            $item->status = $request->status;
            $item->save();

            return response()->json(['success' => true, 'message' => 'Item status changed.']);
        }

        return response()->json(['success' => false, 'message' => 'Invalid Password.']);
    }

    function optimizedImage($path) {

        $filename = \Illuminate\Support\Str::uuid();
        $compressImgPath = 'items/compressed/'.\Carbon\Carbon::now()->format('Y-m-d').'/'.$filename;
        $thumbsImgPath = 'items/thumbs/'.\Carbon\Carbon::now()->format('Y-m-d').'/'.$filename;
        $original = 'items/original/'.\Carbon\Carbon::now()->format('Y-m-d').'/'.$filename;

        // Compressed Image
        $compressedImgWebp = Image::make($path)->resize(900, 900)->encode('webp');
        Storage::put($compressImgPath.'.webp', $compressedImgWebp);

        $compressedImgJpg = Image::make($path)->resize(900, 900)->encode('jpg');
        Storage::put($compressImgPath.'.jpg', $compressedImgJpg);

        // Thumbs
        $thumbsImg = Image::make($path)->resize(300, 300)->encode('jpg');
        Storage::put($thumbsImgPath.'.jpg', $thumbsImg);

        // Original
        $originnalImage = Image::make($path)->encode('webp');
        Storage::put($original.'.jpg', $originnalImage);

        return [
            'compressed_img_webp' => $compressImgPath.'.webp',
            'compressed_img_jpg' => $compressImgPath.'.jpg',
            'thumbs_img' => $thumbsImgPath.'.jpg',
            'original' => $original.'.webp'
        ];
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
