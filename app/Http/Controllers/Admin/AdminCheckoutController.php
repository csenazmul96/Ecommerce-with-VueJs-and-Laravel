<?php

namespace App\Http\Controllers\Admin;

use App\Model\Promotion;
use PDF;
use Mail;
use Session;
use Validator;
use CreditCard;
use Carbon\Carbon;
use App\Model\Page;
use App\Model\User;
use App\Model\Order;
use App\Model\State;
use App\Model\Coupon;
use App\Model\Country;
use App\Model\ItemInv;
use App\Model\CartItem;
use App\Model\MetaBuyer;
use App\Model\OrderItem;
use App\Enumeration\Role;
use App\Model\MetaVendor;
use App\Model\StoreCredit;
use App\Model\VendorImage;
use Illuminate\Http\Request;
use App\Model\ShippingMethod;
use App\Model\AdminShipMethod;
use App\Enumeration\CouponType;
use App\Enumeration\OrderStatus;
use Illuminate\Support\Facades\DB;
use App\Model\BuyerAddress;
use Illuminate\Support\Facades\URL;
use App\Enumeration\PageEnumeration;
use App\Enumeration\VendorImageType;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Contracts\Encryption\DecryptException;

class AdminCheckoutController extends Controller
{
    public function create(Request $request) {

        $sessionUser = session('order_customer_id' , null);

        $user = User::find($sessionUser);

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
        
        $storeCredit = 0;

        // if ($request->storeCredit && is_numeric($request->storeCredit)) {
        //     $sc = StoreCredit::where('user_id', Auth::user()->id)->first();

        //     if (!$sc) {
        //         return response()->json(['success' => false, 'message' => 'Insufficient Store Credit.']);
        //     }

        //     if ($sc->amount < $request->storeCredit)
        //         return response()->json(['success' => false, 'message' => 'Insufficient Store Credit.']);
        //     else
        //         $storeCredit = (float) $request->storeCredit;
        // }

        // calculate total amount
        $cartItems = CartItem::where('user_id', $order->user_id)->get();

        $subTotal = 0;
        foreach ($cartItems as $cartItem) {
            $subTotal += $cartItem->quantity * $cartItem->item->price;
        }

        // Cart Items
        $cartItems = CartItem::where('user_id', $order->user_id)->get();
        $subTotal = 0;
        $orderNumber = $this->generateRandomString(13);

        foreach ($cartItems as $cartItem) {  
            $subTotal += number_format($cartItem->quantity *  $cartItem->item->price, 2, '.',''); 
            OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $cartItem->item_id,
                'style_no' => $cartItem->item->style_no,
                'color' => (isset($cartItem->color) ? $cartItem->color->id : null),
                'color_name' => (isset($cartItem->color) ? $cartItem->color->name : null),
                'size' => (isset($cartItem->itemsize) ? $cartItem->itemsize->id : null),
                'size_name' => (isset($cartItem->itemsize) ? $cartItem->itemsize->item_size : null),
                'qty' => $cartItem->quantity,
                'total_qty' => $cartItem->quantity,
                'per_unit_price' => $cartItem->item->price,
                'amount' => number_format( $cartItem->item->price, 2, '.', ''),
            ]);
        }

        $descountflash=$order->discount;
        $promotion = null;
        $getdescount=0;

        if(!empty($promotion)){
            if($subTotal >= $promotion->credit){
                if($promotion->type==2){
                    $descount=number_format($subTotal * $promotion->amount /100);
                    $getdescount = $descountflash + $descount;
                }else{
                    $getdescount = $descountflash + $promotion->amount;
                }
            }
        }

        $order->order_number = $orderNumber;
        $order->subtotal = number_format($subTotal, 2, '.', '');
        $order->discount = (string) $getdescount;
        $order->shipping_cost = 0;
        $order->total = number_format($subTotal-$storeCredit-$getdescount, 2, '.', '');

        if ($storeCredit > $subTotal)
            $storeCredit = $subTotal;

        $order->store_credit = $storeCredit;

        $order->save();

        return response()->json(['success' => true, 'message' => encrypt($order->id)]);
    }

    public function singlePageCheckout(Request $request){
        $sessionUser = session('order_customer_id' , null);

        $user = User::find($sessionUser);

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

        $countries = Country::orderBy('name')->get();
        $usStates = State::where('country_id', 1)->orderBy('name')->get()->toArray();
        $caStates =State::where('country_id', 2)->orderBy('name')->get()->toArray();

        if ($order->shipping_address_id == null)
            $address = BuyerAddress::where('user_id', $user->id)->where('set_default', 1)->first();
        else
            $address = BuyerAddress::where('id', $order->shipping_address_id)->first();

        $shippingAddresses = BuyerAddress::where('user_id', $user->id)->with('state', 'country')->get();
        $shipping_methods = AdminShipMethod::with('courier')->get();

        return view('admin.dashboard.admin_order.single', compact('address', 'shippingAddresses', 'user',
            'shipping_methods', 'order', 'countries', 'usStates', 'caStates'))->with('page_title', 'Checkout');
    }

    public function singlePageCheckoutPost(Request $request) {

        $sessionUser = session('order_customer_id' , null);

        $user = User::find($sessionUser);

        $rules = [
            'address_id' => 'required',
            'paymentMethod' => 'required|integer|min:1|max:3',
            'shipping_method' => 'required',
        ];

        if ($request->paymentMethod == '2') {
            $rules['number'] = 'required|max:191|min:6';
            $rules['name'] = 'required|max:191';
            $rules['expiry'] = 'required|date_format:"m/y"';
            $rules['cvc'] = 'required';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
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
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        // Check Orders
        $id = '';
        try {
            $id = decrypt($request->id);
        } catch (DecryptException $e) {

        }

        if ($id == '')
            abort(404);
        $order = Order::where('id', $id)->where('status', OrderStatus::$INIT)->first();


        if (!$order)
            abort(404);

        $shipmentMethod = AdminShipMethod::where('id', $request->shipping_method)->first();

        if ($shipmentMethod->fee === null)
            $shipmentMethod->fee = 0;

        if ($request->paymentMethod != '3') {
            $orderCount = Order::count();

            $order->order_number = 'HG'.str_pad($orderCount+1, 6, "0", STR_PAD_LEFT);

            $order->status = OrderStatus::$NEW_ORDER;
        }

        if($request->address_id != 0  ){  
            $shippingAddress = BuyerAddress::where('id', $request->address_id)->with('state', 'country')->first();
             
            $order->shipping_location = $shippingAddress->location;
            $order->shipping_address_id = $request->address_id;
            $order->shipping_address = $shippingAddress->address;
            $order->shipping_unit = $shippingAddress->unit;
            $order->shipping_city = $shippingAddress->city;
            $order->shipping_state = ($shippingAddress->state == null) ? $shippingAddress->state_text : $shippingAddress->state->code;
            $order->shipping_state_id = $shippingAddress->state_id; 
            $order->shipping_state_text = $shippingAddress->state_text;
            $order->shipping_zip = $shippingAddress->zip;
            $order->shipping_country = $shippingAddress->country->name;
            $order->shipping_country_id = $shippingAddress->country->id;
            $order->shipping_phone = $shippingAddress->phone;
            $order->shipping_cost = $shipmentMethod->fee;
        }

        $order->user_id = $user->id;
        $order->email = $user->email;
        $order->shipping_method_id = $shipmentMethod->id;
        $order->shipping = $shipmentMethod->name;
        $order->can_call = $request->can_call;

        // Point system start
        $pointSetting = DB::table('point_system_settings')->first();
        //calculate point for order
        if(!empty($pointSetting)){
            $newPoints = ($pointSetting->point_rewards * $order->subtotal) / $pointSetting->cost_dollars;
            $newPoints = (int) floor($newPoints);

            $oldPoints = MetaBuyer::select('points')->where('user_id',$user->id)->first();
            $oldPoints =  $oldPoints->points;
            $totalPoints = $oldPoints + $newPoints;

            $order->points = $newPoints;
            MetaBuyer::where('user_id',$user->id)->update(['points'=> $totalPoints]);
        }
        // Point system end


        $order->total = number_format(($order->subtotal - $order->discount) + ($shipmentMethod->fee - $order->store_credit), 2, '.', '');
        if ($request->paymentMethod == '2') {
            $order->card_number = encrypt($request->number);
            $order->card_full_name = encrypt($request->name);
            $order->card_expire = encrypt($request->expiry);
            $order->card_cvc = encrypt($request->cvc);

            $order->payment_type = 'Credit Card';
            $card = CreditCard::validCreditCard($request->number);
            $order->payment_type = $card['type'];

        } else if ($request->paymentMethod == '1') {
            $order->payment_type = 'Wire Transfer';
        } else if ($request->paymentMethod == '3') {
            $order->payment_type = 'PayPal';
        }

        $order->note = $request->order_note;
        $order->save();

        $user = $user;
        $user->increment('order_count');

        $cartItems = CartItem::where('user_id', $user->id)->get();
        foreach ($cartItems as $cartItem) {
            $itemInventory = ItemInv::where('item_id', $cartItem->item_id)->first();
            
            if(isset($itemInventory)){
                ItemInv::where('item_id', $cartItem->item_id)
                        ->update([
                            'qty' => $itemInventory->qty - $cartItem->quantity
                        ]);
            }
        }

        CartItem::where([])->where('user_id', $user->id)->delete();

        $pdfData = $this->getPdfData($order);

        try {
            $order_time = Carbon::parse($order->created_at)->format('F d ,Y h:i:s A ');

            // Send Mail to Buyer
            // Mail::send('emails.buyer.order_confirmation', ['order' => $order, 'order_time' => $order_time], function ($message) use ($order, $pdfData) {
            //     $message->subject('Order Confirmed');
            //     $message->to($order->email, $order->name);
            //     $message->attachData($pdfData, $order->order_number . '.pdf');
            // });

            // Send Mail to Vendor
            $user = User::where('role', Role::$EMPLOYEE)->first();

            // Mail::send('emails.vendor.new_order', ['order' => $order], function ($message) use ($order, $pdfData, $user) {
            //     $message->subject('New Order - '.$order->order_number);
            //     $message->to($user->email, $user->first_name.' '.$user->last_name);
            //     $message->attachData($pdfData, $order->order_number.'.pdf');
            // });
        } catch (\Exception $exception) {

        }

        return view('admin.dashboard.admin_order.complete', compact('order'))->with('page_title', 'Checkout Complete');
    }

    public function addressSelect(Request $request) {
        try {
            $id = decrypt($request->id);
        } catch (DecryptException $e) {
            $id = '';
        }

        Order::where('id', $id)->update(['shipping_address_id' => $request->shippingId]);
    }

    public function addShippingAddress(Request $request) {

        $sessionUser = session('order_customer_id' , null);

        $user = User::find($sessionUser);

        $state_id = null;
        $state = null;

        if ($request->location == "INT")
            $state = $request->state;
        else
            $state_id = $request->stateSelect;

        $address = BuyerAddress::create([
            'user_id' => $user->id,
            'location' => $request->location,
            'address' => $request->address,
            'unit' => $request->unit,
            'city' => $request->city,
            'state_id' => $state_id,
            'state_text' => $state,
            'zip' => $request->zipCode,
            'country_id' => $request->country,
            'phone' => $request->phone,
            'fax' => $request->fax,
        ]);

        return response()->json($address->toArray());
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

    public function getPdfData($order) {
        $allItems = [];
        $order->load('user', 'items');

        // Decrypt
        $cardNumber = '';
        $cardFullName = '';
        $cardExpire = '';
        $cardCvc = '';

        try {
            $cardNumber = decrypt($order->card_number);
            $cardFullName = decrypt($order->card_full_name);
            $cardExpire = decrypt($order->card_expire);
            $cardCvc = decrypt($order->card_cvc);
        } catch (DecryptException $e) {

        }

        $order->card_number = $cardNumber;
        $order->card_full_name = $cardFullName;
        $order->card_expire = $cardExpire;
        $order->card_cvc = $cardCvc;

        foreach($order->items as $item)
            $allItems[$item->item_id][] = $item;

        // Vendor Logo
        $logo_path = '';
        $vendorLogo = VendorImage::where('status', 1)
            ->where('type', VendorImageType::$LOGO)
            ->first();

        if ($vendorLogo)
            $logo_path = public_path($vendorLogo->image_path);

        $vendor = MetaVendor::where('id', 1)->first();
        $order->vendor = $vendor;

        $data = [
            'all_items' => [$allItems],
            'orders' => [$order],
            'logo_paths' => [$logo_path],
            'privacy_policy' => $vendor->order_notice
        ];

        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('admin.dashboard.orders.pdf.with_image', $data);
        return $pdf->output();
    }

    public function applyCoupon(Request $request) {
        return response()->json(['success' => false, 'message' => 'Invalid Coupon.']);
        $sessionUser = session('order_customer_id' , null);

        $user = User::find($sessionUser);

        $order = Order::where('id', $request->id)->where('user_id', $user->id)->first();

        if (!$order)
            return response()->json(['success' => false, 'message' => 'Invalid Order.']);

        $coupon = Coupon::where('name', trim($request->coupon))->first();

        if (!$coupon)
            return response()->json(['success' => false, 'message' => 'Invalid Coupon.']);

        if ($coupon->multiple_use == 0) {
            $previous = Order::where('user_id', $user->id)
                ->where('status', '!=', OrderStatus::$INIT)
                ->where('coupon', $coupon->name)
                ->first();

            if ($previous)
                return response()->json(['success' => false, 'message' => 'Already used this coupon.']);
        }

        $subTotal = $order->subtotal;
        $discount = 0;

        if ($coupon->type == CouponType::$FIXED_PRICE)
            $discount = $coupon->amount;
        else if ($coupon->type == CouponType::$PERCENTAGE){
            $discount = ($coupon->amount / 100) * $subTotal;
        } else if ($coupon->type == CouponType::$FREE_SHIPPING){
            $discount = 0;
        }

        if ($discount > $subTotal) {
            $discount = $subTotal; }

        $descountflash=$order->discount;
        if ($descountflash){
            $Finaldiscount = $discount + $descountflash;
        }
        else {
            $Finaldiscount = $discount;
        }


        $order->discount = $Finaldiscount;
        $order->total = $subTotal - $Finaldiscount;
        $order->coupon = $coupon->name;
        $order->coupon_type = $coupon->type;
        $order->coupon_amount = $coupon->amount;
        $order->coupon_description = $coupon->description;
        $order->save();

        return response()->json(['success' => true, 'message' => 'Success.']);
    }
}
