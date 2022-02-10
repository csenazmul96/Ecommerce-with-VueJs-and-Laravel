<?php

namespace App\Http\Controllers\Api\Buyer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\Item;
use App\Model\WishListItem;
use App\Model\Setting;
use Illuminate\Support\Facades\Auth;

class WishListController extends Controller
{
    public function index(Request $request){
        $items = [];

        if ($this->guard()->check()) {
            $user_id = [$this->guard()->id()];

            $obj = new WishListItem();
            $items = Item::where('status', 1)
                ->whereIn('id', $obj->getItemIds($user_id))
                ->with('images' )
                ->get();
        }

		$default_img = Setting::where('name', 'default-item-image')->first();
        return response()->json(['items' => $items, 'default_img' => $default_img] , 200);
    }
    public function AddToWishlist(Request $request){
        if ($this->guard()->check()) {
            $user_id = $this->guard()->id();
            $item_id = $request->item_id;

            $wishlist = WishListItem::where('user_id', $user_id)
                ->where('item_id', $item_id)->first();

            if ($wishlist) {
                $wishlist->delete();
                return response()->json(['message' => 'Wishlist Remove Successfully!!','status'=>'remove'], 200);
            } else {
                $wishlist = WishListItem::create([
                    'user_id' => $user_id,
                    'item_id' => $item_id
                ]);
                return response()->json(['message' => 'Add To Wishlist Successfully!!', 'status' => 'add', 'wishlist' => $wishlist], 200);
            }
        } else {
            return response()->json(['message' => 'Please login to add wishlist'], 401);
        }
        /*$user_id = $this->guard()->id();
        $user_ip = $request->ip();
        if(!$user_id) {
            $user_id = $user_ip;
            $user = [$user_ip];
        } else {
            $user = [$user_id, $user_ip];
        }
        $item_id = $request->item_id;
        $wishlist = WishListItem::whereIn('user_id', $user)
                                ->where('item_id', $item_id)->first();

        if ($wishlist) {
            $wishlist->delete();
            return response()->json(['message' => 'Wishlist Remove Successfully!!','status'=>'remove'], 200);
        } else {
            $wishlist = WishListItem::create([
                'user_id' => $user_id,
                'item_id' => $item_id
            ]);
            return response()->json(['message' => 'Add To Wishlist Successfully!!', 'status' => 'add', 'wishlist' => $wishlist], 200);
        }*/
    }

    public function remove_wishlist(Request $request) {
        $request->validate([
            'item_id' => 'required|exists:items,id',
        ]);

        $user_id = $this->guard()->id();
        $user_ip = $request->ip();
        if(!$user_id) {
            $user_id = $user_ip;
            $user = [$user_ip];
        } else {
            $user = [$user_id, $user_ip];
        }

        $wishlistItem = WishListItem::where('item_id', $request->item_id)->whereIn('user_id', $user)->first();

        if( $wishlistItem && ($wishlistItem->user_id == $user_id || $wishlistItem->user_id == $user_ip)) {
            $wishlistItem->delete();
            return response()->json(['message' => 'Wishlist Item Romoved Successfully!','status'=>'success'], 200);
        }

        return response()->json(['message' => 'Wishlist Item Romoved Failed!','status'=>'failed'], 200);
    }

    public function guard(){
        return Auth::Guard('api');
    }
}
