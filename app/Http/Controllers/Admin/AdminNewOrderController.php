<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;
use URL;
use File;
use Uuid;
use Excel;
use Image;
use DateTime;
use Carbon\Carbon;
use App\Model\Item;
use App\Model\Pack;
use App\Model\User;
use App\Model\Color;
use App\Model\Fabric;
use GuzzleHttp\Client;
use App\Model\CartItem;
use App\Model\Category;
use App\Model\ItemView;
use App\Enumeration\Role;
use App\Model\ItemImages;
use App\Model\MetaVendor;
use App\Model\MasterColor;
use App\Model\WishListItem;
use App\Model\MadeInCountry;
use Illuminate\Http\Request;
use App\Enumeration\Availability;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Model\MetaBuyer;

class AdminNewOrderController extends Controller
{
    public function itemListAll(Request $request) {
        $activeItemsQuery = Item::query();
        $activeItemsQuery->where('status', 1)
            ->with('category', 'images');
        $TotalactiveItems = Item::where('status', 1)->count();

        //Item Search
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
            }
        } else {
            $activeItemsQuery->orderBy('sorting', 'desc');
        }

        $activeItems = $activeItemsQuery->paginate(40, ['*'], 'p1');

        $appends = [
            'p1' => $activeItems->currentPage(),
        ];

        foreach ($request->all() as $key => $value) {
            if ($key != 'p1' && $key != 'p2')
                $appends[$key] = ($value == null) ? '' : $value;
        }

        $customers = User::where('role', Role::$BUYER)->orderBy('created_at', 'desc')->get();

        $current_customer = session('order_customer_id' , '');

        $selectedCustomer = User::where('id', $current_customer)->first();

        $cart_items = $this->getcartItems();

        $selected_items_list = [];
        $items_list = CartItem::where('user_id', $current_customer)->get()->pluck('item_id');

        foreach ($items_list as $item){
            $selected_items_list[] = $item;
        }

        $sessionUser = session('order_customer_id' , null);

        $userData = User::find($sessionUser);

        return view('admin.dashboard.admin_order.index', compact('userData','TotalactiveItems','activeItems' , 'customers' , 'current_customer' , 'cart_items' , 'selected_items_list', 'appends'))
            ->with('page_title', 'Create New Order');
    }

    public function set_new_customer(Request $request){
        $message = 'New customer is set where id is '.request('customerId');

        if(request('delete_old') == true){
            CartItem::where('user_id', session('order_customer_id'))->delete();

            session(['order_customer_id' => request('customerId')]);

        }

        session(['order_customer_id' => request('customerId')]);

        return response()->json(['success' => false, 'message' => $message]);
    }

    public function nameSearch(Request $request){

        $search = $request->search;

        if($search == ''){
            $users = DB::table('users')
                ->select('users.id','users.first_name','users.email')
                ->where('first_name', 'like', '%' .$search . '%')
                ->where('users.deleted_at', '=', NULL)
                ->get();
        }else{
            $users = DB::table('users')
                ->select('users.id','users.first_name','users.email')
                ->where('first_name', 'like', '%' .$search . '%')
                ->where('users.deleted_at', '=', NULL)
                ->get();
        }

        $response = array();
        foreach($users as $user){
            $response[] = array("value"=>$user->email,"label"=>$user->first_name,"user_id"=>$user->id);
        }

        echo json_encode($response);
        exit;
    }
    public function emailSearch(Request $request){
        $search = $request->search;

        if($search == ''){
            $users = DB::table('users')
                ->select('users.id','users.first_name','users.email')
                ->where('email', 'like', '%' .$search . '%')
                ->get();
        }else{
            $users = DB::table('users')
                ->select('users.id','users.first_name','users.email')
                ->where('email', 'like', '%' .$search . '%')
                ->get();
        }

        $response = array();
        foreach($users as $user){
            $response[] = array("value"=>$user->first_name,"label"=>$user->email,"user_id"=>$user->id);
        }

        echo json_encode($response);
        exit;
    }

    public function getcartItems(){
        $cartItems['total'] = 0;
        $sessionUser = session('order_customer_id');

        $items = CartItem::where('user_id', $sessionUser)->with('item','color')->get();

        $totalPrice = 0;
        $totalQty = 0;
        foreach($items as $item) {
            $qty = $item->quantity;
            $totalPrice += $item->quantity * $item->item->price;
            $totalQty += $item->quantity;

            if (array_key_exists($item->item->id, $cartItems))
                $qty = (int) $cartItems[$item->item->id]['qty'] + (int) $qty;

            $image_path = asset('images/no-image.png');

            if (sizeof($item->item->images) > 0)
                $image_path = Storage::url($item->item->images[0]->thumbs_image_path);

            $cartItems[$item->item->id] = [
                'name' => $item->item->style_no,
                'qty' => $qty,
                'image_path' => $image_path,
                'price' => $item->item->price,
            ];
        }
        $cartItems['total'] = [
            'total_price' => $totalPrice,
            'total_qty' => $totalQty
        ];

        return $cartItems;
    }

    public function set_session(Request $request)
    {
        $request->session()->forget('order_customer_id');
        $request->session()->put('order_customer_id',  $request->id);
    }
}
