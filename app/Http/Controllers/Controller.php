<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Auth;
use App\Model\CartItem;
use App\Model\MetaVendor;
use View;
use DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public $user;
    public function __construct() {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            if(Auth::user()){
                //cart data
                $temp = [];
                $cartItems = [];
                $vendor = MetaVendor::where('id', 1)->first();

                $cartObjs = CartItem::where('user_id', auth()->user()->id)
                    ->with('item', 'color')
                    ->get();

                foreach($cartObjs as $obj) {
                    $temp[$obj->item['id']][] = $obj;
                }   
                
                $itemCounter = 0;

                foreach($temp as $itemId => $item) {
                    $cartItems[$itemCounter] = $item;
                    $itemCounter++;
                }
                View::share('cartData', $cartItems);
            }
            return $next($request);
        });
    }
}
