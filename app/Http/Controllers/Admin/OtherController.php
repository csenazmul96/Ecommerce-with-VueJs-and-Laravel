<?php

namespace App\Http\Controllers\Admin;

use App\Model\Item;
use App\Model\Pack;
use App\Model\Color;
use App\Model\Setting;
use GuzzleHttp\Client;
use App\Model\Category;
use App\Model\MetaVendor;
use App\Model\ItemCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class OtherController extends Controller
{
    public function getItems(Request $request) {
        $query = Item::query();
        $query->where('status', 1);

        if ($request->search && $request->search != '')
            $query->where('style_no', 'like', '%'.$request->search.'%');

        if ($request->category && $request->category != ''){
            $itemcategory = ItemCategory::where('default_parent_category',$request->category)
                            ->select('item_id')
                            ->get();
            $ids = [];
            foreach($itemcategory as $catitem){
                $ids[] = $catitem->item_id;
            }
            $query->whereIn('id', $ids);
        }

        $query->orderBy('activated_at', 'desc');

        $items = $query->paginate(21);
        $paginationView = $items->links();
        $paginationView = trim(preg_replace('/\r\n/', ' ', $paginationView));

        foreach($items as &$item) {
            // Image
            $imagePath = asset('images/no-image.png');

            if (sizeof($item->images) > 0)
                $imagePath = Storage::url($item->images[0]->thumbs_image_path);

            $item->imagePath = $imagePath;
        }

        return ['items' => $items->toArray(), 'pagination' => $paginationView];
    }

    public function buyerHome() {
        $setting = Setting::where('name', 'buyer_home')->first();

        if (!$setting) {
            Setting::create([
                'name' => 'buyer_home',
            ]);
        }

        return view('admin.dashboard.buyer_home.index', compact('setting'))->with('page_title', 'Buyer Home');
    }

    public function buyerHomeSave(Request $request) {
        $setting = Setting::where('name', 'buyer_home')->first();

        if ($setting) {
            $setting->value = $request->buyer_home;
            $setting->save();
        } else {
            Setting::create([
                'name' => 'buyer_home',
                'value' => $request->buyer_home,
            ]);
        }

        return redirect()->back()->with('message', 'Saved!');
    }

    public function welcomeNotification() {
        $setting = Setting::where('name', 'welcome_notification')->first();

        if (!$setting) {
            Setting::create([
                'name' => 'welcome_notification',
            ]);
        }

        return view('admin.dashboard.administration.welcome_notification.index', compact('setting'))->with('page_title', 'Welcome Notification');
    }

    public function topNotification() {
        $setting = Setting::where('name', 'top_notification')->first();

        if (!$setting) {
            Setting::create([
                'name' => 'top_notification',
                'value' => ''
            ]);
        }

        return view('admin.dashboard.administration.top_notification.index', compact('setting'))->with('page_title', 'Top Notification');
    }

    public function topNotificationSave(Request $request) {
        $setting = Setting::where('name', 'top_notification')->first();

        if ($setting) {
            $setting->value = $request->data;
            $setting->desc = $request->notification_bg;
            $setting->save();
        } else {
            Setting::create([
                'name' => 'welcome_notification',
                'value' => $request->data,
                'desc' => $request->notification_bg,
            ]);
        }

        return redirect()->back()->with('message', 'Saved!');
    }

    public function welcomeNotificationSave(Request $request) {
        $setting = Setting::where('name', 'welcome_notification')->first();

        if ($setting) {
            $setting->value = $request->data;
            $setting->save();
        } else {
            Setting::create([
                'name' => 'welcome_notification',
                'value' => $request->data,
            ]);
        }

        return redirect()->back()->with('message', 'Saved!');
    }

    public function exportToSPView(Request $request) {
        $items = Item::whereIn('id', explode(',', $request->ids))->get();

        return view('admin.dashboard.export_stats', compact('items'));
    }

    public function exportToSPPost(Request $request) {
        $v = MetaVendor::where('id', 1)->first();

        $vendor = $v->sp_vendor;
        $password = $v->sp_password;
        $category = $request->v;
        $item = Item::where('id', $request->id)->with('colors', 'images')->first();

        // Default Category
        $defaultCategory = $request->c;

        // Size
        $size = Pack::where('id', $item->pack_id)->first();
        $pack = $size->pack1;

        for($i=2; $i <= 10; $i++) {
            $var = 'pack'.$i;

            if ($size->$var != null)
                $pack .= '-'.$size->$var;
        }

        // Colors
        $color = '';
        foreach ($item->colors as $c)
            $color .= $c->name.',';

        $color = trim($color, ',');


        // Images
        $images = '';
        $imageColors = [];

        foreach ($item->images as $img) {
            $images .= asset($img->image_path) . ',';

            $c = '';

            if ($img->color_id != null) {
                $tmp = Color::where('id', $img->color_id)->first();
                $c = $tmp->name;
            }

            $imageColors[] = $c;
        }

        $images = trim($images, ',');

        $url = config('custom.sp_url');
        $client = new Client();
        $res = $client->post($url.'api/create/item', [
            'form_params' => [
                'username' => $vendor,
                'password' => $password,
                'styleno' => $item->style_no,
                'itemname' => $item->name,
                'vendorcategory' => $category,
                'defaultcategory' => $defaultCategory,
                'size' => $size->name,
                'pack' => $pack,
                'unitprice' => $item->price,
                'originalprice' => $item->orig_price,
                'availableon' => $item->available_on,
                'productdescription' => $item->description,
                'fabric' => $item->fabric,
                'madein' => ($item->made_in_id != null ? $item->madeInCountry->name : null),
                'color' => $color,
                'images' => $images,
                'images_color' => implode(',', $imageColors),
            ]
        ]);

        return response()->json(json_decode($res->getBody()->getContents()));
    }

    public function product_hover_status(Request $request){
        $setting = Setting::where('name', 'item_hover_status')->first();
        if ($setting) {
            $setting->value = $request->status;
            $setting->save();
        } else {
            Setting::create([
                'name' => 'item_hover_status',
                'value' => $request->status,
            ]);
        }
    }
}
