<?php

namespace App\Http\Controllers\Admin;

use DB;
use Carbon\Carbon;
use App\Model\Item;
use App\Model\User;
use App\Model\Order;
use App\Model\Coupon;
use App\Model\ItemInv;
use App\Model\CartItem;
use App\Enumeration\Role;
use App\Model\MetaVendor;
use App\Model\PromoCodes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminCartController extends Controller
{
    public function addToCart(Request $request){
        $sessionUser = $request->current_customer;
        $request->session()->forget('order_customer_id');
        $request->session()->put('order_customer_id',  $sessionUser);

        $item = Item::where('status', 1)->where('id', $request->itemId)->first();
        $itemOrder = 0;
        $itemOrder = (int) $request->qty;

        $itemInventory = ItemInv::where('item_id', $request->itemId)->where('color_id', $request->colors)->first();
        if (isset($itemInventory) && $itemOrder > $itemInventory->qty)
            return response()->json(['success' => false, 'message' => 'maximum quantity is : '. $itemInventory->qty]);

        if (isset($itemInventory) && empty($itemInventory->qty))
            return response()->json(['success' => false, 'message' => 'Item is sold out']);

        $cartItem = CartItem::where([
            ['user_id', $sessionUser],
            ['item_id', $request->itemId],
            ['color_id', $request->colors],
            ['size_id', $request->size],
        ])->first();
        if ($cartItem) {
            $cartItem->quantity += (int) $request->qty;
            $cartItem->save();
        } else {
            CartItem::create([
                'user_id' => $sessionUser,
                'item_id' => $request->itemId,
                'color_id' => $request->colors,
                'size_id' => $request->size,
                'quantity' => $request->qty,
            ]);
        }
        $count = CartItem::where('user_id', $sessionUser)->get()->sum('quantity');
        return response()->json(['success' => true, 'message' => 'Success','count'=>$count]);
    }

    public function addToCartPromo(Request $request){
        $input = $request->input();
        $check = Coupon::where('name', $input['code'])->get()->first();
        if ( $check == null ) {
            session()->forget('promo');
            return redirect()->back()->withErrors(['error' => 'Invalid Promo Code']);
        }
        if ( $check->multiple_use == 1 ) {
            session()->push('promo', $check->toArray());
            return redirect()->back()->withErrors(['message' =>  'Promo code successfully added']);
        }
        else {
            $checkPromoUsed = Order::where('user_id', Auth::user()->id)->where('promo_code_id', $check->id)->get();
            if ( $checkPromoUsed->isEmpty() ) {
                session()->push('promo', $check->toArray());
                return redirect()->back()->withErrors(['message' =>  'Promo code successfully added']);
            }
            else {
                return redirect()->back()->withErrors(['error' => 'You have Used This Promo Code Once!']);
            }
        }
    }

    public function addToCartSuccess(){
        return back()->with('message', 'Added to cart.');
    }

    public function showCart(){
        $sessionUser = session('order_customer_id' , null);
        $user = User::find($sessionUser);

        $temp = [];
        $cartItems = [];
        $vendor = MetaVendor::where('id', 1)->first();

        $cartObjs = CartItem::where('user_id', $user->id)
            ->with('item', 'color', 'itemsize')
            ->get();

        foreach($cartObjs as $obj) {
            $temp[$obj->item->id][] = $obj;
        }

        $itemCounter = 0;

        foreach($temp as $itemId => $item) {
            $cartItems[$itemCounter] = $item;
            $itemCounter++;
        }
        return view('admin.dashboard.admin_order.cart', compact('cartItems', 'vendor','user'))->with('page_title', 'Admin Order Cart');
    }

    public function updateCart(Request $request){
        $data = [];
        for($i=0; $i < count($request->ids); $i++) {
            $ci = CartItem::where('id', $request->ids[$i])->first();
            $c = 0;
            if (isset($data[$ci->item->id]))
            $c = $data[$ci->item->id]['qty'] ;

            $data[$i]['id'] = $ci->item->id;
            $data[$i]['color'] = $ci->color_id;
            $data[$i]['size'] = $ci->size_id;
            $data[$i]['qty'] = (int)($request->qty[$i]) + $c;
        }
        foreach ($data as $arr) {
            $item = Item::where('id', $arr['id'])->first();

            // if ($item->min_qty > $arr['qty'])
            //     return response()->json(['success' => false, 'message' => $item->style_no.' minimum order qty is '. $item->min_qty]);

            $itemInventory = ItemInv::where('item_id', $arr['id'])->where('color_id', $arr['color'])->first();

            if (isset($itemInventory) && $arr['qty'] > $itemInventory->qty)
                return response()->json(['success' => false, 'message' => 'maximum quantity is : '. $itemInventory->qty]);

            if (isset($itemInventory) && empty($itemInventory->qty))
                return response()->json(['success' => false, 'message' => 'Item is sold out']);
        }

        for($i=0; $i < sizeof($request->ids); $i++) {
            if ($request->qty[$i] == '0')
                CartItem::where('id', $request->ids[$i])->delete();
            else {
                CartItem::where('id', $request->ids[$i])->update(['quantity' => $request->qty[$i]]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Success']);

    }

    public function cartItemColor(Request $request){
        $color = DB::table('color_item')
            ->join('colors','color_item.color_id','=','colors.id')
            ->select('color_item.color_id','colors.name')
            ->where('item_id', $request->itemId)
            ->where('available', 1)
            ->get();

        $size = DB::table('item_size')
            ->join('sizes','item_size.size_id','=','sizes.id')
            ->select('item_size.size_id','sizes.name')
            ->where('item_id', $request->itemId)
            ->get();

        $colorSize = array('color' => $color, 'size'=> $size);

        return $colorSize;
    }

    public function cartItemSize(Request $request) {
        $sizes = ItemInv::where('item_id', $request->itemId)
            ->where('color_id', $request->colorId)
            ->with('size')
            ->get()->toArray();

        return response()->json($sizes);
    }

    public function updateCartSuccess(){
        return back()->with('message', 'Cart Updated!');
    }

    public function deleteCart(Request $request){
        CartItem::where('item_id', $request->itemId)->where('size_id', $request->sizeId)->delete();
    }

    public function deleteCartAll(Request $request){
        CartItem::where([])->delete();
    }
}
