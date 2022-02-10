<?php

namespace App\Http\Controllers\Api\Buyer;

use App\Model\User;
use Illuminate\Contracts\Encryption\DecryptException;
use net\authorize\api\controller as AnetController;
use net\authorize\api\contract\v1 as AnetAPI;
use Illuminate\Support\Facades\Auth;
use App\Enumeration\PageEnumeration;
use App\Http\Controllers\Controller;
use App\Model\BuyerShippingAddress;
use Illuminate\Support\Facades\DB;
use App\Enumeration\OrderStatus;
use App\Model\AdminShipMethod;
use Illuminate\Http\Request;
use App\Model\AuthorizeLog;
use App\Model\StoreCredit;
use App\Model\MetaVendor;
use App\Model\Promotion;
use App\Model\OrderItem;
use App\Model\MetaBuyer;
use App\Model\CartItem;
use App\Model\ItemInv;
use App\Model\Country;
use App\Model\Order;
use App\Model\Page;
use App\Model\State;
use Carbon\Carbon;
use CreditCard;
use Validator;
use Mail;
use PDF;
use Log;

class CheckoutController extends Controller
{
    //
    public function create(Request $request) {
        $user = Auth::user();

        $cartItems = CartItem::with('item')->get();
        $user->load('buyer');
        $billing_location = '';
        $billing_address = '';
        $billing_unit = '';
        $billing_city = '';
        $billing_state = '';
        $billing_state_id = '';
        $billing_state_text = '';
        $billing_zip = '';
        $billing_country = '';
        $billing_country_id = '';
        $billing_phone = '';
        if($user->buyer){
            $billing_location = $user->buyer->billing_location;
            $billing_address = $user->buyer->billing_address;
            $billing_unit = $user->buyer->billing_unit;
            $billing_city = $user->buyer->billing_city;
            $billing_state = ($user->buyer->billingState == null) ? $user->buyer->billing_state : $user->buyer->billingState->code;
            $billing_state_id = $user->buyer->billing_state_id;
            $billing_state_text = $user->buyer->billing_state;
            $billing_zip = $user->buyer->billing_zip;
            $billing_country = $user->buyer->billingCountry;
            $billing_country_id = $user->buyer->billing_country_id;
            $billing_phone = $user->buyer->billing_phone;
        }
        $meta_vendor = MetaVendor::where('verified', 1)->where('active', 1)->get()->first();
        $minimum_order_amount = ( isset($meta_vendor->min_order) && $meta_vendor->min_order != '' ) ? $meta_vendor->min_order : 0;
        $order = Order::create([
            'status' => OrderStatus::$INIT,
            'user_id' => $user->id,
            'name' => $user->first_name.' '.$user->last_name,
            'email' => $user->email,
            'billing_location' => $billing_location,
            'billing_address' => $billing_address,
            'billing_unit' => $billing_unit,
            'billing_city' => $billing_city,
            'billing_state' => $billing_state,
            'billing_state_id' => $billing_state_id,
            'billing_state_text' => $billing_state_text,
            'billing_zip' => $billing_zip,
            'billing_country' => $billing_country,
            'billing_country_id' => $billing_country_id,
            'billing_phone' => $billing_phone,
        ]);
            // calculate total amount
        $cartItems = CartItem::with('item')->where('user_id', $order->user_id)->get();

        $subTotal = 0;
        foreach ($cartItems as $cartItem) {
            $subTotal +=  $cartItem->item->price * $cartItem->quantity;
        }

        if ( $minimum_order_amount > $subTotal ) {
            return response()->json(['success' => false, 'message' => 'Minimum order amount is : ' . $minimum_order_amount]);
        }
        // Cart Items
        $cartItems = CartItem::with('item')->where('user_id', $order->user_id)->get();
        $subTotal = 0;
        $orderNumber = $this->generateRandomString(13);

        foreach ($cartItems as $cartItem) {
            $subTotal += number_format($cartItem->quantity *  $cartItem->item->price, 2, '.','');
            OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $cartItem->item_id,
                'style_no' => $cartItem->item->style_no,
                'color' => $cartItem->color_id,
                'size' => $cartItem->size_id,
                'qty' => $cartItem->quantity,
                'total_qty' => $cartItem->quantity,
                'per_unit_price' => $cartItem->item->price,
                'amount' => number_format( $cartItem->item->price, 2, '.', ''),
                ]);
        }
        $order = $this->getMaximumDiscount($order->id, $subTotal);
        $orderNumber = $this->generateRandomString(13);
        $order->order_number = $orderNumber;
        $order->shipping_cost = 0;
        $order->total = number_format($subTotal - $order->discount, 2, '.', '');

        $order->save();
        return response()->json(['success' => true, 'message' => encrypt($order->id)]);
    }

    public function generateRandomString($length) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public function getMaximumDiscount($orderId, $subTotal) {

        $maximum_discount = 0;
        $optimizez_order = null;

        $coupons = Promotion::where('hasCouponCode', 0)->where('status', 1)->get();


        if(count($coupons) == 0) {
            $order = Order::where('id', $orderId)->first();
            $order->free_shipping = 0;
            $order->subtotal = number_format($subTotal, 2, '.', '');
            $order->discount = 0.00;
            $order->promotion_details = '';
            $order->shipping_cost = 0.00;
            return $order;
        }


        foreach($coupons as $coupon) {

            $order = Order::where('id', $orderId)->first();

            $discountflash = 0.00;
            $discount = 0;

            $coupon_details = '';
            $free_shipping = 0;

            $valid = 1;

            if($coupon->is_permanent == 0) {


                $startDate = strtotime($coupon->valid_from);
                $endDate = strtotime($coupon->valid_to);

                $currentTime = time();

                if($currentTime > $startDate && $currentTime < $endDate) {

                } else {

                    $valid = 0;

                }

            }

            $alreadyUsed = 0;

            if ($coupon->multiple_use == 0) {
                if(Auth::user()){
                    $previous = Order::where('user_id', Auth::user()->id)
                        ->where('status', '!=', OrderStatus::$INIT)
                        ->where('default_coupon_id', $coupon->id)
                        ->first();

                    if ($previous) {

                        $alreadyUsed = 1;

                    }
                }
            }

            if($valid == 1 && $alreadyUsed == 0) {

                if($coupon->to_price_1) {
                } else {
                    $coupon->to_price_1 = 1000000;
                }

                if($subTotal >= $coupon->from_price_1 && $subTotal <= $coupon->to_price_1) {

                    if($coupon->promotion_type == 'Percentage discount by order amount') {

                        $discount = number_format($discountflash + ($coupon->percentage_discount_1 / 100) * $subTotal, 2, '.', '');
                        $coupon_details = '["' . $coupon->title . '" - ' . $coupon->percentage_discount_1 . '%]';

                    } else {

                        $discount = number_format($discountflash + $coupon->unit_price_discount_1,2, '.', '');
                        $coupon_details = '["' . $coupon->title . '" - $' . $coupon->unit_price_discount_1 . ']';

                    }

                    if($coupon->free_shipping_1 == 1) {

                        $free_shipping = 1;

                    }

                } else {

                    if($coupon->to_price_2) {
                    } else {
                        $coupon->to_price_2 = 1000000;
                    }

                    if($subTotal >= $coupon->from_price_2 && $subTotal <= $coupon->to_price_2) {

                        if($coupon->promotion_type == 'Percentage discount by order amount') {

                            $discount = number_format($discountflash + ($coupon->percentage_discount_2 / 100) * $subTotal, 2, '.', '');
                            $coupon_details = '["' . $coupon->title . '" - ' . $coupon->percentage_discount_2 . '%]';

                        } else {

                            $discount = number_format($discountflash + $coupon->unit_price_discount_2, 2, '.', '');
                            $coupon_details = '["' . $coupon->title . '" - $' . $coupon->unit_price_discount_2 . ']';

                        }

                        if($coupon->free_shipping_2 == 1) {

                            $free_shipping = 1;

                        }

                    } else {

                        if($coupon->to_price_3) {
                        } else {
                            $coupon->to_price_3 = 1000000;
                        }

                        if($subTotal >= $coupon->from_price_3 && $subTotal <= $coupon->to_price_3) {

                            if($coupon->promotion_type == 'Percentage discount by order amount') {

                                $discount = number_format($discountflash + ($coupon->percentage_discount_3 / 100) * $subTotal, 2, '.', '');
                                $coupon_details = '["' . $coupon->title . '" - ' . $coupon->percentage_discount_3 . '%]';

                            } else {

                                $discount = number_format($discountflash + $coupon->unit_price_discount_3, 2, '.', '');
                                $coupon_details = '["' . $coupon->title . '" - $' . $coupon->unit_price_discount_3 . ']';

                            }

                            if($coupon->free_shipping_3 == 1) {

                                $free_shipping = 1;

                            }

                        } else {

                            if($coupon->to_price_4) {
                            } else {
                                $coupon->to_price_4 = 1000000;
                            }

                            if($subTotal >= $coupon->from_price_4 && $subTotal <= $coupon->to_price_4) {

                                if($coupon->promotion_type == 'Percentage discount by order amount') {

                                    $discount = number_format($discountflash + ($coupon->percentage_discount_4 / 100) * $subTotal, 2, '.', '');
                                    $coupon_details = '["' . $coupon->title . '" - ' . $coupon->percentage_discount_4 . '%]';

                                } else {

                                    $discount = number_format($discountflash + $coupon->unit_price_discount_4, 2, '.', '');
                                    $coupon_details = '["' . $coupon->title . '" - $' . $coupon->unit_price_discount_4. ']';

                                }

                                if($coupon->free_shipping_4 == 1) {

                                    $free_shipping = 1;

                                }

                            } else {

                                if($coupon->to_price_5) {
                                } else {
                                    $coupon->to_price_5 = 1000000;
                                }

                                if($subTotal >= $coupon->from_price_5 && $subTotal <= $coupon->to_price_5) {

                                    if($coupon->promotion_type == 'Percentage discount by order amount') {

                                        $discount = number_format($discountflash + ($coupon->percentage_discount_5 / 100) * $subTotal, 2, '.', '');
                                        $coupon_details = '["' . $coupon->title . '" - ' . $coupon->percentage_discount_5 . '%]';

                                    } else {

                                        $discount = number_format($discountflash + $coupon->unit_price_discount_5, 2, '.', '');
                                        $coupon_details = '["' . $coupon->title . '" - $' . $coupon->unit_price_discount_5 . ']';

                                    }

                                    if($coupon->free_shipping_5 == 1) {

                                        $free_shipping = 1;

                                    }

                                }

                            }
                        }
                    }

                }

                $order->default_coupon_id = $coupon->id;
                $order->free_shipping = $free_shipping;

            }

            $order->subtotal = number_format($subTotal, 2, '.', '');
            $order->discount = $discount;
            $order->promotion_details = $coupon_details;
            $order->shipping_cost = 0;

            if($optimizez_order == null) {
                $optimizez_order = $order;
            }

            if($discount > $maximum_discount) {

                $maximum_discount = $discount;
                $optimizez_order = $order;

            }

        }

        return $optimizez_order;

    }

    public function singlePageCheckout(Request $request ) {
        $user = Auth::user();
        $user->load('buyer');
        // Check Orders
        $id = '';
        try {
            $id = decrypt($request->id);
        } catch (DecryptException $e) {
        }

        if ($id == '')
            abort(404);

        $order = Order::where('id', $id)->first();
        $shipping_methods = AdminShipMethod::with('courier')->get();
        // Vendor Logo
        $logo_path = '';
        $black = DB::table('settings')->where('name', 'logo-black')->first();
        if ($black){
            $logo_path = asset($black->value);
        }
        $shippingaddress = BuyerShippingAddress::where('user_id', Auth::user()->id)->with('state', 'country')->get();

        return response()->json([ 'order' => $order, 'shipping_methods' => $shipping_methods, 'logo_path' =>$logo_path,'shippingaddress'=> $shippingaddress], 200);
    }

    public function singlePageCheckoutPost(Request $request) {
        if ($request->address_id == null) {
            $rules['address_id'] = 'required';
        }
        if($request->address_id == 0){
            $rules['address']= 'required';
            $rules['city'] = 'required';
            $rules['zipCode'] = 'required';
            $rules['state'] = 'required_without_all:stateSelect';
            $rules['stateSelect'] = 'required_without_all:state';
            $rules['country'] = 'required';
        }
        if ($request->paymentMethod == '2') {
            $rules['number'] = 'required|max:191|min:6';
            $rules['name'] = 'required|max:191';
            $rules['expiry'] = 'required|date_format:"m/y"';
            $rules['cvc'] = 'required';
        }
        $rules['paymentMethod'] = 'required|integer|min:1|max:3';
        $rules['shipping_method'] = 'required' ;
        $rules['factoryAddress'] = 'required';
        $rules['factoryZipCode'] = 'required';
        $rules['factoryCountry'] = 'required';
        $rules['factoryStateSelect'] = 'required_without_all:factoryState';
        $rules['factoryState'] = 'required_without_all:factoryStateSelect';
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->messages()], 200);
        }

        if ($request->paymentMethod == '2') {
            $validator->after(function ($validator) use($request) {
                // Card Number Check
                $card = CreditCard::validCreditCard($request->number);

                if (!$card['valid'])
                    $validator->errors()->add('number', 'Invalid Card Number');

                // CVC Check
                $validCvc = CreditCard::validCvc($request->cvc, $card['type']);
                if (!$validCvc)
                    $validator->errors()->add('cvc', 'Invalid CVC');

                // Expiry Check
                $tmp  = explode('/', $request->expiry);
                $validDate = CreditCard::validDate('20'.$tmp[1], $tmp[0]);
                if (!$validDate)
                    $validator->errors()->add('expiry', 'Invalid Expiry');
            });

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->messages()], 200);
            }
        }
        // Check Orders
        $id = '';
        try {
            $id = decrypt($request->id);
        } catch (DecryptException $e) {

        }

        if ($id == '')
            return response()->json(['message'=>'Invalid Order'], 200);

        $user = Auth::user();
        if(!$user){
                $this->register($request);
        }

        $order = Order::where('id', $id)->where('status', OrderStatus::$INIT)->first();

        if (!$order)
            return response()->json(['message'=>'Invalid Order'], 200);

        $shipmentMethod = AdminShipMethod::where('id', $request->shipping_method)->first();


        if ($shipmentMethod->fee === null)
            $shipmentMethod->fee = 0;

        if($order->free_shipping == 1) {
            $shipmentMethod->fee = 0;
        }


        // Point system start

        $pointSetting = DB::table('point_system_settings')->first();
        //calculate point for order
        if(!empty($pointSetting)){
            $newPoints = ($pointSetting->point_rewards * $order->subtotal) / $pointSetting->cost_dollars;
            $newPoints = (int) floor($newPoints);

            $oldPoints = MetaBuyer::select('points')->where('user_id',Auth::user()->id)->first();
            $oldPoints =  $oldPoints->points;
            $totalPoints = $oldPoints + $newPoints;

            $order->points = $newPoints;
            MetaBuyer::where('user_id',Auth::user()->id)->update(['points'=> $totalPoints]);
        }

        $percentageTotal = 0;
        if($request->reward_point){
            $rewardData = PointSystem::where('id',$request->reward_point)->first();
            if($rewardData->free_shipping_1 == 1){
                $shipmentMethod->fee = 0;
            }
            if($rewardData->percentage_discount_1 != 0){
                $percentageTotal = $order->subtotal * ($rewardData->percentage_discount_1 / 100);
                $order->reward_percantage = number_format($percentageTotal, 2, '.', '');
            }
            if($rewardData->unit_price_discount_1 != 0){

                $percentageTotal = $rewardData->unit_price_discount_1;
                $order->reward_fixed = number_format($percentageTotal, 2, '.', '');
            }
            $oldSpentPoints = MetaBuyer::select('points','points_spent')->where('user_id',Auth::user()->id)->first();
            $totalSpentPoints = $oldSpentPoints->points_spent + $rewardData->from_price_1;
            MetaBuyer::where('user_id',Auth::user()->id)->update(['points_spent'=> $totalSpentPoints]);

            $order->reward_point = $request->reward_point;
            $order->used_point = $rewardData->from_price_1;
        }

        // Point system end

        $order->user_id = Auth::user()->id;
        $order->email = Auth::user()->email;
        $order->company_name = Auth::user()->buyer->company_name;
        $order->shipping_method_id = $shipmentMethod->id;
        $order->shipping = $shipmentMethod->name;
        $order->can_call = $request->can_call;

        $factory_state_id = null;
        $factory_state = null;
        $factory_country = null;

        if ($request->factoryLocation == "INT"){
            $factory_state = $request->factoryState;
        }else{
            $factory_state_id = $request->factoryStateSelect;
            $factory_state_name = State::Select('name')->where('id',$request->factoryStateSelect)->first();
            $factory_state = $factory_state_name->name;
        }

        if ($request->factoryLocation == "CA")
            $factory_country = 'Canada';
        if ($request->factoryLocation == "US")
            $factory_country = 'United States';

        // Order Shipping Information insert

        if($request->address_id != 0  ){
            $shippingAddress = BuyerShippingAddress::where('id', $request->address_id)->with('state', 'country')->first();

            $order->shipping_location = $shippingAddress->location;
            $order->shipping_address = $shippingAddress->address;
            $order->shipping_unit = $shippingAddress->unit;
            $order->shipping_city = $shippingAddress->city;
            $order->shipping_state = ($shippingAddress->state == null) ? $shippingAddress->state_text : $shippingAddress->state->code;
            $order->shipping_state_id = $shippingAddress->state_id;
            $order->shipping_zip = $shippingAddress->zip;
            $order->shipping_country = $shippingAddress->country->name;
            $order->shipping_country_id = $shippingAddress->country->id;
            $order->shipping_phone = $shippingAddress->phone;

        }else{
            $country = Country::where('id',$request->country)->first();
            $State='';
            if(empty($request->state)){
                $State = State::where('id',$request->stateSelect)->first();
                $order->shipping_state =  $State->name;
                $order->shipping_state_id = $State->id;
            }else{
                $order->shipping_state =  '';
                $order->shipping_state_id = '';
                $order->shipping_state_text = $request->state;
            }
            $order->shipping_location = $request->location;
            $order->shipping_address = $request->address;
            $order->shipping_city = $request->city;
            $order->shipping_zip = $request->zipCode;
            $order->shipping_country = $country->name;
            $order->shipping_country_id = $country->id;
            $order->shipping_phone = $request->phone;
        }

        $order->shipping_cost = $shipmentMethod->fee;
        // Order billing Information insert
        $order->billing_location = $request->factoryLocation;
        $order->billing_address = $request->factoryAddress;
        $order->billing_city = $request->factoryCity;
        $order->billing_state_id = $factory_state_id;
        $order->billing_zip = $request->factoryZipCode;
        $order->billing_phone = $request->factoryPhone;
        $order->billing_country_id = $request->factoryCountry;
        $order->billing_country = $factory_country;
        $order->billing_state = $factory_state;

        $order->total = number_format(($order->subtotal - $order->discount  - $percentageTotal) + ($shipmentMethod->fee ), 2, '.', '');

        if ($request->paymentMethod == '2') {
            $order->card_number = encrypt($request->number);
            $order->card_full_name = encrypt($request->name);
            $order->card_expire = encrypt($request->expiry);
            $order->card_cvc = encrypt($request->cvc);
            $order->token    = $request->stripeToken;

            $order->payment_type = 'Credit Card';
            $card = CreditCard::validCreditCard($request->number);
            $order->payment_type = $card['type'];

        } else if ($request->paymentMethod == '1') {
            $order->payment_type = 'Wire Transfer';
        } else if ($request->paymentMethod == '3') {
            $order->payment_type = 'PayPal';
        }

        $order->note = $request->order_note;
        $order->status = OrderStatus::$NEW_ORDER;
        $order->save();

// store credit option
        //Get the order ID created just now
        $last_order_id = $order->id;


        $timestr =  Carbon::now();
        $timestr =  $timestr->toDateTimeString();



        $user->increment('order_count');

        // User Billing Information meta data update
        $usermeta = MetaBuyer::where('user_id',$user->id)->first();
        $usermeta->billing_location = $request->factoryLocation;
        $usermeta->billing_address = $request->factoryAddress;
        $usermeta->billing_city = $request->factoryCity;
        $usermeta->billing_state_id = $factory_state_id;
        $usermeta->billing_zip = $request->factoryZipCode;
        $usermeta->billing_phone = $request->factoryPhone;
        $usermeta->billing_country_id = $request->factoryCountry;
        $usermeta->billing_unit = $request->factoryUnit;
        $usermeta->billing_state = $factory_state;
        $usermeta->save();
        if($request->address_id !=null){
            $userShippingAddressD= BuyerShippingAddress::where('id',$request->address_id)->where('user_id',$user->id)->update(['default'=> 1]);
            $userShippingAddress= BuyerShippingAddress::where('id','<>',$request->address_id)->where('user_id',$user->id)->update(['default'=> 0]);
        }


        $pdfData = $this->getPdfData($order);
        // Send Mail to Buyer
        $order_time = Carbon::parse($order->created_at)->format('F d ,Y h:i:s A ');
        $orderItem = OrderItem::where('order_id', $order->id)->count('item_id');
        Mail::send('emails.buyer.order_confirmation', ['order' => $order, 'order_time' => $order_time, 'order_item' => $orderItem], function ($message) use ($order, $pdfData) {
            $message->subject('Order Confirmed');
            $message->to($order->email, $order->name);
            $message->attachData($pdfData, $order->order_number . '.pdf');
        });

        //Send Mail to Vendor
        $user = User::where('role', Role::$EMPLOYEE)->first();
        Mail::send('emails.vendor.new_order', ['order' => $order, 'order_time' => $order_time], function ($message) use ($order, $pdfData, $user) {
            $message->subject('New Order - '.$order->order_number);
            $message->to($user->email, $user->first_name.' '.$user->last_name);
            $message->attachData($pdfData, $order->order_number.'.pdf');
        });


        $cartItems = CartItem::where('user_id', Auth::user()->id)->get();
        foreach ($cartItems as $cartItem) {
            $itemInventory = ItemInv::where('item_id', $cartItem->item_id)->where('color_id', $cartItem->color->id)->first();
            if(isset($itemInventory)){
                ItemInv::where('item_id', $cartItem->item_id)->where('color_id', $cartItem->color->id)->update([
                    'qty' => $itemInventory->qty - $cartItem->quantity
                ]);
            }
        }

        CartItem::where([])->where('user_id',auth()->user()->id)->delete();

        return response()->json(['message'=>'success'], 200);
    }

    public function register($request){
        $metaBuyer = MetaBuyer::create([
            'verified' => 0,
            'active' => 0,
            'user_id' => 0,
            'receive_offers' =>0,
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'email' => $request->email,
            'last_name' => $request->last_name,
            'password' => bcrypt($request->password),
            'role' => \App\Enumeration\Role::$BUYER,
            'buyer_meta_id' => $metaBuyer->id,
        ]);

        $metaBuyer->user_id = $user->id;
        $metaBuyer->save();

        return $this->login();

    }


    public function ApplyCoupon(Request $request) {
        $order = Order::where('id', $request->id)->where('user_id', Auth::user()->id)->first();

        if (!$order)
            return response()->json(['success' => false, 'message' => 'Invalid Order.']);

        $previous_discount = $order->discount;
        $discountFlash = 0.00;
        $coupon = Promotion::where('coupon_code', strtoupper(trim($request->coupon)))->where('status', 1)->first();
        if (!$coupon)
            return response()->json(['success' => false, 'message' => 'Invalid Coupon.']);

        if($order->coupon != null)
            return response()->json(['success' => false, 'message' => 'You have already used "' . $order->coupon .'" in this order.']);

        $previous = Order::where('user_id', Auth::user()->id)
                ->where('id', $request->id)
                ->where('status', 1)
                ->first();

        if ($previous->coupon == strtoupper(trim($request->coupon)))
            return response()->json(['success' => false, 'message' => 'Coupon code has already been used once.']);

        if($coupon->is_permanent == 0) {


            $startDate = strtotime($coupon->valid_from);
            $endDate = strtotime($coupon->valid_to);

            $currentTime = time();

            if($currentTime > $startDate && $currentTime < $endDate) {

            } else {

                return response()->json(['success' => false, 'message' => 'Coupon Expired.']);

            }

        }

        if ($coupon->multiple_use == 0) {
            $previous = Order::where('user_id', Auth::user()->id)
                ->where('status', '!=', OrderStatus::$INIT)
                ->where('coupon', $coupon->coupon_code)
                ->first();

            if ($previous)
                return response()->json(['success' => false, 'message' => 'Coupon code has already been used once.']);
        }

        $subTotal = $order->subtotal;
        $discount = 0;
        $coupon_details = '';
        $free_shipping = 0;

        if($coupon->to_price_1) {
        } else {
            $coupon->to_price_1 = 1000000;
        }

        if($subTotal >= $coupon->from_price_1 && $subTotal <= $coupon->to_price_1) {

            if($coupon->promotion_type == 'Percentage discount by order amount') {

                $discount = number_format($discountFlash + ($coupon->percentage_discount_1 / 100) * $subTotal, 2, '.', '');
                $coupon_details = '["' . $coupon->coupon_code . '" - ' . $coupon->percentage_discount_1 . '%]';

            } else {

                $discount = number_format($discountFlash + $coupon->unit_price_discount_1, 2, '.', '');
                $coupon_details = '["' . $coupon->coupon_code . '" - $' . $coupon->unit_price_discount_1 . ']';

            }

            if($coupon->free_shipping_1 == 1) {
                $free_shipping = 1;
            }

        } else {

            if($coupon->to_price_2) {
            } else {
                $coupon->to_price_2 = 1000000;
            }

            if($subTotal >= $coupon->from_price_2 && $subTotal <= $coupon->to_price_2) {

                if($coupon->promotion_type == 'Percentage discount by order amount') {

                    $discount = number_format($discountFlash + ($coupon->percentage_discount_2 / 100) * $subTotal, 2, '.', '');
                    $coupon_details = '["' . $coupon->coupon_code . '" - ' . $coupon->percentage_discount_2 . '%]';

                } else {

                    $discount = number_format($discountFlash + $coupon->unit_price_discount_2, 2, '.', '');
                    $coupon_details = '["' . $coupon->coupon_code . '" - $' . $coupon->unit_price_discount_2 . ']';

                }

                if($coupon->free_shipping_2 == 1) {
                    $free_shipping = 1;
                }

            } else {

                if($coupon->to_price_3) {
                } else {
                    $coupon->to_price_3 = 1000000;
                }

                if($subTotal >= $coupon->from_price_3 && $subTotal <= $coupon->to_price_3) {

                    if($coupon->promotion_type == 'Percentage discount by order amount') {

                        $discount = number_format($discountFlash + ($coupon->percentage_discount_3 / 100) * $subTotal, 2, '.', '');
                        $coupon_details = '["' . $coupon->coupon_code . '" - ' . $coupon->percentage_discount_3 . '%]';

                    } else {

                        $discount = number_format($discountFlash + $coupon->unit_price_discount_3, 2, '.', '');
                        $coupon_details = '["' . $coupon->coupon_code . '" - $' . $coupon->unit_price_discount_3 . ']';

                    }

                    if($coupon->free_shipping_3 == 1) {
                        $free_shipping = 1;
                    }

                } else {

                    if($coupon->to_price_4) {
                    } else {
                        $coupon->to_price_4 = 1000000;
                    }

                    if($subTotal >= $coupon->from_price_4 && $subTotal <= $coupon->to_price_4) {

                        if($coupon->promotion_type == 'Percentage discount by order amount') {

                            $discount = number_format($discountFlash + ($coupon->percentage_discount_4 / 100) * $subTotal, 2, '.', '');
                            $coupon_details = '["' . $coupon->coupon_code . '" - ' . $coupon->percentage_discount_4 . '%]';

                        } else {

                            $discount = number_format($discountFlash + $coupon->unit_price_discount_4, 2, '.', '');
                            $coupon_details = '["' . $coupon->coupon_code . '" - $' . $coupon->unit_price_discount_4 . ']';

                        }

                        if($coupon->free_shipping_4 == 1) {
                            $free_shipping = 1;
                        }

                    } else {

                        if($coupon->to_price_5) {
                        } else {
                            $coupon->to_price_5 = 1000000;
                        }

                        if($subTotal >= $coupon->from_price_5 && $subTotal <= $coupon->to_price_5) {

                            if($coupon->promotion_type == 'Percentage discount by order amount') {

                                $discount = number_format($discountFlash + ($coupon->percentage_discount_5 / 100) * $subTotal, 2, '.', '');
                                $coupon_details = '["' . $coupon->coupon_code . '" - ' . $coupon->percentage_discount_5 . '%]';

                            } else {

                                $discount = number_format($discountFlash + $coupon->unit_price_discount_5, 2, '.', '');
                                $coupon_details = '["' . $coupon->coupon_code . '" - $' . $coupon->unit_price_discount_5 . ']';

                            }

                            if($coupon->free_shipping_5 == 1) {
                                $free_shipping = 1;
                            }

                        }

                    }
                }
            }

        }

        if($discount > $previous_discount) {

            $order->discount = $discount;
            $order->total = number_format($subTotal - $order->store_credit - $discount, 2, '.', '');
            $order->coupon = $coupon->coupon_code;
            $order->default_coupon_id = null;
            $order->promotion_details = $coupon_details;
            $order->free_shipping = $free_shipping;

            $order->save();

            return response()->json(['success' => true, 'message' => 'Success.', 'discount' => $order->discount, 'total' => $order->total, 'free_shipping' => $order->free_shipping]);

        } else {

            return response()->json(['success' => false, 'message' => 'You can use only one promotion in a single order.']);

        }
    }


}
