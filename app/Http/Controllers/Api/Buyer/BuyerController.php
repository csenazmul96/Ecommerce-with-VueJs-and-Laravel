<?php

namespace App\Http\Controllers\Api\Buyer;

use App\Model\BuyerAddress;
use App\Model\State;
use App\Model\Statistic;
use CardDetect\Detector;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Model\User;
use App\Model\MetaBuyer;
use App\Enumeration\PageEnumeration;
use App\Enumeration\Role;
use App\Model\Page;
use App\Model\Order;
use App\Model\OrderItem;
use App\Model\StoreCredit;
use App\Model\CartItem;
use App\Model\CartCoupon;
use App\Model\ItemInv;
use App\Model\MetaVendor;
use App\Enumeration\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use CreditCard;
use LVR\CreditCard\CardCvc;
use LVR\CreditCard\CardExpirationDate;
use LVR\CreditCard\CardNumber;
use Validator;
use Mail;
use PDF;
use Log;
use DB;
use Session;
use App\Model\Promotion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB as FacadesDB;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use App\Model\AuthorizeLog;

// payment
use Omnipay\Omnipay;
use Omnipay\Common\CreditCard as NewCreditCard;
use Stripe\Stripe;

class BuyerController extends Controller
{
    protected $stripe;

    /**
     * Create a new BuyerController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
        $this->middleware('auth:api')->except(['checkout']);
    }
    /**
     * Get a JWT via given credentials. (Register)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profileUpdate(Request $request){
        $user = Auth::user();
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
        ]);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->save();
    }
    public function changePassword(Request $request){
        $request->validate([
            'current_password' => 'required' ,
            'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
        ]);

        $user = Auth::user();
        if (!$user){
            return response()->json(['message' => 'User Not Found'], 200);
        } else{
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['errors' => ['current_password' => ['Old Password Not Metch.','status'=>'failed']]], 422);
            }else{
                $user->password = Hash::make($request->password);
                $user->reset_token = $request->token;
                $user->save();
                return response()->json(['message' => 'Password Updated Successfully.','status'=>'success'], 200);
            }
        }
    }

    public function register($data, $request){
        $metaBuyer = MetaBuyer::create([
            'verified' => 0,
            'active' => 0,
            'user_id' => 0,
            'shipping_location' => $request['shippingInput']['location'],
            'shipping_address' => $request['shippingInput']['address'],
            'shipping_city' => $request['shippingInput']['city'],
            'shipping_state_id' => $request['shippingInput']['state_id'],
            'shipping_state' => isset($request['shippingInput']['state_text']) ? $request['shippingInput']['state_text'] : null,
            'shipping_unit' => $request['shippingInput']['unit'] ?? null,
            'shipping_zip' => $request['shippingInput']['zip'],
            'shipping_country_id' => $request['shippingInput']['country_id'],
            'shipping_phone' => $request['shippingInput']['phone'],
            'shipping_fax' => isset($request['shippingInput']['fax']) ? $request['shippingInput']['fax'] : null,
            'billing_location' => $request['billingInput']['location'],
            'billing_address' => $request['billingInput']['address'],
            'billing_city' => $request['billingInput']['city'],
            'billing_state_id' => $request['billingInput']['state_id'],
            'billing_state' => isset($request['billingInput']['state_text']) ? $request['billingInput']['state_text'] : null,
            'billing_unit' => $request['billingInput']['unit'] ?? null,
            'billing_zip' => $request['billingInput']['zip'],
            'billing_country_id' => $request['billingInput']['country_id'],
            'billing_phone' => $request['billingInput']['phone'],
            'billing_fax' => isset($request['billingInput']['fax']) ? $request['billingInput']['fax'] : null,
            'receive_offers' => 1,
        ]);

        $user = User::create([
            'first_name' => $data['first_name'],
            'email' => $data['email'],
            'active' => 0,
            'last_name' => $data['last_name'],
            'password' => bcrypt($data['password']),
            'role' => \App\Enumeration\Role::$BUYER,
            'buyer_meta_id' => $metaBuyer->id,
        ]);

        $metaBuyer->user_id = $user->id;
        $metaBuyer->save();

        return $user->load('buyer');

    }
    public function checkout(Request $request) {
        $rules['userInput'] = "required|array";
        $rules['userInput.first_name'] = "required|string";
        $rules['userInput.last_name'] = "required|string";
        $rules['userInput.email'] = "required|email";

        $rules['billingInput'] = "required|array";
        $rules['billingInput.address'] = "required|string";
        $rules['billingInput.location'] = "required";
        $rules['billingInput.country_id'] = "required|exists:countries,id";
        $rules['billingInput.zip'] = "required";
        $rules['billingInput.phone'] = "required";
        $rules['billingInput.city'] = "required";

        $rules['shippingInput'] = "required|array";
        $rules['shippingInput.address'] = "required|string";
        $rules['shippingInput.location'] = "required";
        $rules['shippingInput.country_id'] = "required|exists:countries,id";
        $rules['shippingInput.state_id'] = "nullable|exists:states,id";
        $rules['shippingInput.zip'] = "required";

        $rules['shippingMethod'] = "required|array";
        $rules['shippingMethod.id'] = "required|exists:admin_ship_methods,id";
        $rules['shippingMethod.fee'] = "nullable|numeric";
        $rules['shippingMethod.name'] = "required|string";

        $rules['paymentMethod'] = 'required|integer|min:1|max:3';
        $rules['paymentId'] = 'nullable';
        $rules['note'] = 'nullable|string';
        $rules['user_point'] = 'nullable|numeric|min:0';
        if(!isset($request['billingInput']['id'])){
            $rules['billingInput.state_id'] = "required_without:billingInput.state_text";
            $rules['billingInput.state_text'] = "required_without:billingInput.state_id";
        }
        if(!isset($request['shippingInput']['id'])){
            $rules['shippingInput.state_id'] = "required_without:shippingInput.state_text";
            $rules['shippingInput.state_text'] = "required_without:shippingInput.state_id";
        }

        $freeShipping = $request->freeShipping ? 1 : 0;
        if ($request->paymentMethod == '2') {
            $rules['cardInput'] = "required|array";
            $rules['cardInput.card_number'] = 'required|max:191|min:6';
            $rules['cardInput.card_number'] = ['required', new CardNumber];
            $rules['cardInput.card_expire'] = 'required|date_format:"m/y"';
            $rules['cardInput.card_expire'] = ['required', 'date_format:"m/y"', new CardExpirationDate('m/y')];
            $rules['cardInput.card_cvc'] = 'required';
            $rules['cardInput.card_cvc'] = ['required', new CardCvc($request['cardInput']['card_number'])];
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->messages()], 422);
        }

        if($request->grandTotal <= 0 || $request->grandTotal== null ){
            $validator->after(function ($validator) use($request) {
                $validator->errors()->add('grandTotal', 'Grand Total Ammount Should Be More Than Ziro.');
            });
            if ($validator->fails()) {
                return response()->json(['errors'=>$validator->messages()], 422);
            }
        }
        $user = $this->guard()->user();
        if(!$user)
            $user = User::where('email', $request->userInput['email'])->with('buyer')->first();

        if(!$user){
            $rules['userInput.email'] = "required|string|email|max:255";
            $rules['userInput.password'] = "min:6|required_with:userInput.password_confirmation|same:userInput.password_confirmation";
            $rules['userInput.password_confirmation'] = "min:6";
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails())
                return response()->json(['errors'=>$validator->messages()], 422);

            $data = [
                'first_name' => $request->userInput['first_name'],
                'last_name' => $request->userInput['last_name'],
                'email' => $request->userInput['email'],
                'password' => $request->userInput['password'],
            ];
            $user = $this->register($data, $request);
        }
        $checkFirstOrder = Order::where('user_id', $user->id)->count();

        if($request['shippingInput']['id'] === '-1'){
            $this->storeNewShippingAddress($request, $user);
        }
        if($request['billingInput']['id'] === '-1'){
            $this->storeNewBillingAddress($request, $user);
        }

        $store_credit = 0;
        if($request->storeCredit && $user){
            $userCredit = StoreCredit::where('user_id',$user->id)->first();
            $userCredit->amount = $userCredit->amount - $request->storeCredit;
            $userCredit->save();
            $store_credit = $request->storeCredit;
        }

        // card validation
        if ($request->paymentMethod == '2') {

            $validator->after(function ($validator) use($request) {
                try {
                    // Card Number Check
                    $card = CreditCard::validCreditCard($request['cardInput']['card_number']);

                    if (!$card['valid'])
                        $validator->errors()->add('cardInput.card_number', 'Invalid Card Number');

                    // CVC Check
                    $validCvc = CreditCard::validCvc($request['cardInput']['card_cvc'], $card['type']);
                    if (!$validCvc)
                        $validator->errors()->add('cardInput.card_cvc', 'Invalid CVC');

                    // Expiry Check
                    $tmp = explode('/', $request['cardInput']['card_expire']);
                    $validDate = CreditCard::validDate('20' . $tmp[1], $tmp[0]);
                    if (!$validDate)
                        $validator->errors()->add('cardInput.card_expire', 'Invalid Expiry');
                } catch (\Exception $e) {

                }
            });

            if ($validator->fails()) {
                return response()->json(['errors'=>$validator->messages()], 422);
            }
        }

        $state = [];
        if(isset($request['billingInput']['state_id'])){
            $state = State::find($request['billingInput']['state_id']);
        }

        $shippingstate = [];
        if(isset($request['shippingInput']['state_id'])){
            $shippingstate = State::find($request['shippingInput']['state_id']);
        }

        DB::beginTransaction();
        try {
            // check is exists user email
            $orderData = [
                'status' => OrderStatus::$NEW_ORDER,
                'note' =>  $request['note'],
                'user_id' => $user->id,
                'company_name' => ($user && $user->buyer) ? $user->buyer->company_name : '',
                'name' => $request['userInput']['first_name'].' '.$request['userInput']['last_name'],
                'email' => $request['userInput']['email'],
                'can_call' => 0,

                'b_user_name' => $request['billingInput']['first_name']. ' '. $request['billingInput']['last_name'],
                'billing_location' => $request['billingInput']['location'],
                'billing_address' => $request['billingInput']['address'],
                'billing_unit' => $request['billingInput']['unit'] ?? null,
                'billing_city' => $request['billingInput']['city'],
                'billing_state' => isset($request['billingInput']['state_id']) ? $state->name : $request['billingInput']['state_text'],
                'billing_state_id' => $request['billingInput']['state_id'],
                'billing_state_text' => isset($request['billingInput']['state_text']) ? $request['billingInput']['state_text'] : null,
                'billing_zip' => $request['billingInput']['zip'],
                'billing_country' => isset($request['billingInput']['country']) ? $request['billingInput']['country']['name'] : null,
                'billing_country_id' => $request['billingInput']['country_id'],
                'billing_phone' => $request['billingInput']['phone'],

                's_user_name' => $request['shippingInput']['first_name']. ' '. $request['shippingInput']['last_name'],
                'shipping_location' => $request['shippingInput']['location'],
                'shipping_address' => $request['shippingInput']['address'],
                'shipping_unit' => $request['shippingInput']['unit'] ?? null,
                'shipping_city' => $request['shippingInput']['city'],
                'shipping_state' => isset($request['shippingInput']['state_id']) ? $shippingstate->name : $request['shippingInput']['state_text'],
                'shipping_state_id' => $request['shippingInput']['state_id'],
                'shipping_state_text' => isset($request['shippingInput']['state_text']) ? $request['shippingInput']['state_text'] : null,
                'shipping_zip' => $request['shippingInput']['zip'],
                'shipping_country' => isset($request['shippingInput']['country']) ? $request['shippingInput']['country']['name'] : null,
                'shipping_country_id' => $request['shippingInput']['country_id'],
                'shipping_phone' => $request['shippingInput']['phone'],
            ];

            if ($request->paymentMethod == '2') {
                $orderData['card_number'] = encrypt($request['cardInput']['card_number']);
                $orderData['card_full_name'] = encrypt($request['cardInput']['card_full_name']);
                $orderData['card_expire'] = encrypt($request['cardInput']['card_expire']);
                $orderData['card_cvc'] = encrypt($request['cardInput']['card_cvc']);
                $cardDetector = new Detector();
                $card = CreditCard::validCreditCard($request->number);
                $orderData['payment_type'] = $cardDetector->detect($request['cardInput']['card_number']);;
            } else if ($request->paymentMethod == '1') {
                $orderData['payment_type'] = 'Wire Transfer';
            } else if ($request->paymentMethod == '3') {
                $orderData['payment_type'] = 'PayPal';
            }
            $orderData['payment_id'] = $request->paymentId;
            $orderData['shipping_method_id'] = $request['shippingMethod']['id'];
            $orderData['shipping'] = $request['shippingMethod']['name'];
            $orderData['shipping_cost'] = $request['shippingMethod']['fee'] ? $request['shippingMethod']['fee'] : 0.00;

            $order = Order::create($orderData);

            // Cart Items
            if($request->guest_id){
                $carts = CartItem::where('user_id', $request->guest_id)->get();
                foreach($carts as $item){
                    $item->user_id = $user->id;
                    $item->save();
                }
            }
            $userArray=[];
            $cartItems = CartItem::with('item')->where('user_id', $user->id)->get();
            $subTotal = 0;
            $userArray[0] =$user->id;

            foreach ($cartItems as $cartItem) {
                $subTotal += number_format($cartItem->quantity *  $cartItem->item->price, 2, '.','');
                OrderItem::create([
                    'order_id' => $order->id,
                    'item_id' => $cartItem->item_id,
                    'style_no' => $cartItem->item->style_no,
                    'color' => (isset($cartItem->color) ? $cartItem->color->id : null),
                    'size' => (isset($cartItem->itemsize) ? $cartItem->itemsize->id : null),
                    'color_name' => (isset($cartItem->color) ? $cartItem->color->name : null),
                    'size_name' => (isset($cartItem->itemsize) ? $cartItem->itemsize->item_size : null),
                    'qty' => $cartItem->quantity,
                    'total_qty' => $cartItem->quantity,
                    'per_unit_price' => $cartItem->item->price,
                    'amount' => number_format( $cartItem->item->price * $cartItem->quantity, 2, '.', ''),
                ]);
                $statistic = Statistic::where('cart_id', $cartItem->id)->first();
                if($statistic) {
                    $statistic->status = 'order';
                    $statistic->user_id = $user->id;
                    $statistic->order_id = $order->id;
                    $statistic->price = $cartItem->item->price;
                    $statistic->total_price = number_format($cartItem->item->price * $cartItem->quantity, 2, '.', '');
                    $statistic->save();
                }
            }

            $order->free_shipping = $freeShipping;
            $order->subtotal = number_format($subTotal, 2, '.', '');
            $order->discount = 0.00;
            $order->promotion_details = '';
            $order->coupon = '';
            $orderCount = Order::count();

            $coupon = app('App\Http\Controllers\Api\CartController')->getCoupon($user->id, [$user->id]);
            try {
                if(!empty($coupon)){
                    $coupon =  json_decode($coupon->content(), true);
                }
            }catch (\Exception $ex) {

            }

            if (!$coupon) {
//                $order->free_shipping = 0;
                $order->subtotal = number_format($subTotal, 2, '.', '');
                $order->discount = 0.00;
                $order->promotion_details = '';
                $order->coupon = '';
            } else {
//                $order->free_shipping = $coupon->free_shipping;
                $order->subtotal = number_format($subTotal, 2, '.', '');
                $order->discount = number_format($coupon["coupon"]["discount"], 2, '.', '');
                $order->promotion_details = $coupon["coupon"]["coupon_details"];
                $order->coupon = $coupon["coupon"]["coupon_code"];
                if ($freeShipping == 1) {
                    $order->shipping_cost = 0.00;
                } else {
                    $order->shipping_cost = $request['shippingMethod']['fee'];
                }
                CartCoupon::where('user_id', $user->id)->delete();
            }

            $order->order_number = 'HG'.str_pad($orderCount+1, 6, "0", STR_PAD_LEFT);
            $order->store_credit = number_format($store_credit,2,'.','');
            $order->total = number_format($subTotal - $order->discount , 2, '.', '');
            $order->save();


            //point system start
            $dollarDiscount = 0;
            if($request->user_point){
                $oldDollarSpentPoints = MetaBuyer::select('points_spent')->where('user_id', $user->id)->first();
                $finalSpent = $oldDollarSpentPoints->points_spent + $request->user_point;
                MetaBuyer::where('user_id', $user->id)->update(['points_spent' => $finalSpent]);
                $order->used_dollar_point = $request->user_point;
                $pointDollarDiscount = DB::table('point_dollar_discount')->first();
                $dollar_point_discount = ($pointDollarDiscount->dollar_disounts * $request->user_point) / $pointDollarDiscount->points_use;
                $order->dollar_point_discount = number_format($dollar_point_discount, 2, '.', '');
                $dollarDiscount = $dollar_point_discount;
            }

            $pointSetting = DB::table('point_system_settings')->where('status',1)->first();
            //calculate point for order
            $total = ($order->subtotal - $order->discount - $order->store_credit - $dollarDiscount) + $order->shipping_cost;
            if(!empty($pointSetting) && $user){
                $newPoints = number_format(($pointSetting->point_rewards * $total) / $pointSetting->cost_dollars, 2, '.', '');
                $oldPoints = MetaBuyer::select('points')->where('user_id', $user->id)->first();
                $oldPoints =  $oldPoints->points;
                $totalPoints = $oldPoints + $newPoints;
                $order->points = number_format($newPoints, 2, '.', '');
                MetaBuyer::where('user_id', $user->id)->update(['points'=> number_format($totalPoints, 2, '.', '')]);
            }
            $order->total = number_format($total, 2, '.', '');
            $order->save();


            $cartItems = CartItem::whereIn('user_id', $userArray)->get();
            foreach ($cartItems as $cartItem) {
                $itemInventory = ItemInv::where('item_id', $cartItem->item_id)->where('color_id', $cartItem->color_id)->where('itemsize', $cartItem->size_id)->first();
                if(isset($itemInventory)){
                    ItemInv::where('item_id', $cartItem->item_id)->where('color_id', $cartItem->color_id)->where('itemsize', $cartItem->size_id)->update([
                        'qty' => $itemInventory->qty - $cartItem->quantity
                    ]);
                }
            }

            $cartItems = CartItem::whereIn('user_id', $userArray)->delete();

            if($user) {
                $user->increment('order_count');
            }

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
        DB::commit();

//       $this->authorizeAndCapture($order->id);
        $this->StripeAuthorizeAndCapture($order->id);

        // Send Mail to Buyer
        $order_time = Carbon::parse($order->created_at)->format('F d ,Y h:i:s A ');
        $orderItem = OrderItem::where('order_id', $order->id)->count('item_id');

        $black = DB::table('settings')->where('name', 'logo-black')->first();

        if ($black){
            $black_logo_path = asset($black->value);
        }
        $order->load('items');
        // send mail to buyer
        Mail::send('emails.buyer.new_order', ['order' => $order, 'order_time' => $order_time, 'order_item' => $orderItem, 'logo'=>$black_logo_path], function ($message) use ($order) {
            $message->subject('Order Received');
            $message->to($order->email, $order->name);
        });
        // send mail to admin
        Mail::send('emails.admin.new_order', ['order' => $order, 'order_time' => $order_time, 'order_item' => $orderItem, 'logo'=>$black_logo_path], function ($message) use ($order) {
            $message->subject('Order Received');
            $message->to(env('MAIL_ADMIN'), $order->name);
        });

        return response()->json(['success' => true, 'message' => 'Order Completed. And progresssing e-mail ', 'order_number' => $order->order_number], 200);
    }

    public function StripeAuthorizeAndCapture($id)
    {
        $order = Order::where('id', $id)->with('user', 'items')->first();
        $date = decrypt($order->card_expire);
        $new_date = explode('/',$date);
        $orderDesc = '';
        $oInc = 1;
        foreach ($order->items as $oitem){
            $orderDesc.= $oInc.'. Style No: '. $oitem->style_no .', ';
            $oInc = $oInc + 1;
        }
        try {
            $cardToken = $this->stripe->tokens->create([
                'card' => [
                    'number' => decrypt($order->card_number),
                    'exp_month' => $new_date[0],
                    'exp_year' => $new_date[1],
                    'cvc' => decrypt($order->card_cvc),
                    'name' => decrypt($order->card_full_name),
                ]
            ]);
            $order->token = $cardToken->id;
            $order->save();

            $amount = (float)(implode('.', explode('.', $order->total)));
            $response = $this->stripe->charges->create([
                'amount' => $amount * 100,
                'currency' => 'usd',
                'source' => $cardToken->id,
                'description' => $orderDesc,
                'capture' => true,
                'shipping' => [
                    "address" => [
                        "city" => $order->shipping_city,
                        "country" => $order->shipping_country,
                        "line1" => $order->shipping_address,
                        "postal_code" => $order->shipping_zip,
                        "state" => $order->shipping_state ?? $order->shipping_state_text
                    ],
                    "name" => $order->name,
                ]
            ]);


            $order->payment_id = $response->id ?? null;
            $data['transaction_code'] = $response->balance_transaction ?? null;
            $data['authorized_time'] = Carbon::now();
            $data['status'] = 'Success';
            if ($response->status === 'succeeded') {
                $order->aStatus = 'Success';
                $order->authorize_info = json_encode(['status' => 'Success', 'message' => 'Purchase transaction was successful!', 'response_data' => $data]);
                $order->save();
                return response()->json(['success' => true, 'message' => 'Purchase transaction was successful!', 'response_data' => $data]);
            } else {
                $order->aStatus = 'Failed';
                $order->authorize_info = json_encode(['status' => 'Failed', 'message' => 'Purchase transaction was successful!']);
                $order->save();
                return response()->json(['success' => false, 'message' => 'Purchase transaction was Unsuccessful!', 'response_data' => $data]);
            }

        }catch (\Exception $e){
            $order->aStatus = 'Failed';
            $order->authorize_info = json_encode(['status' => 'Failed', 'message' => 'Purchase transaction was Not successful!']);
            $order->save();
            return response()->json(['success' => false, 'message' => 'Exception caught while attempting purchase. Error Message: ' . $e->getMessage(), 'exception_type' => get_class($e)]);
        }
    }


    public function authorizeAndCapture($orderId) {
        $order = Order::where('id', $orderId)->with('user', 'items')->first();
        $orderDesc = 'Style No: ';
        foreach ($order->items as $oitem){
            $orderDesc.= $oitem->style_no .', ';
        }

        $invoiceId = $order->order_number;

        $authorize_info = $order->authorize_info;

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
        $fName =  $order->user->first_name;
        $lName =  $order->user->last_name;
        $b_address =  $order->billing_address;
        $b_city =  $order->billing_city;
        $b_state =  $order->billing_state;
        $b_zip =  $order->billing_zip;
        $b_country =  $order->billing_country;
        $user_id = $order->user->id;
        $user_email = $order->user->email;


        $s_address =  $order->shipping_address;
        $s_city =  $order->shipping_city;
        $s_state =  $order->shipping_state;
        $s_zip =  $order->shipping_zip;
        $s_country =  $order->shipping_country;


        $amount = $order->total;
        $expireData = explode('/', $cardExpire);
        $exYear = 2000 + intval($expireData[1]);
        $exMonth = $expireData[0];
        $expiry = $exYear.'-'.$exMonth;

        // Common setup for API credentials
        // define("AUTHORIZENET_LOG_FILE", storage_path('logs/laravel.log'));
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(config('services.authorize.login'));
        $merchantAuthentication->setTransactionKey(config('services.authorize.key'));
        $refId = 'ref'.time();


        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($cardNumber);
        $creditCard->setExpirationDate($expiry);
        $creditCard->setCardCode($cardCvc);

        // Add the payment data to a paymentType object
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setCreditCard($creditCard);


        // Create order information
        $orderInfo = new AnetAPI\OrderType();
        $orderInfo->setInvoiceNumber($invoiceId);
        $orderInfo->setDescription($orderDesc);



        // Set the customer's Bill To address
        $customerAddress = new AnetAPI\CustomerAddressType();
        $customerAddress->setFirstName($fName);
        $customerAddress->setLastName($lName);
        //$customerAddress->setCompany("Souveniropolis");
        $customerAddress->setAddress($b_address);
        $customerAddress->setCity($b_city);
        $customerAddress->setState($b_state);
        $customerAddress->setZip($b_zip);
        $customerAddress->setCountry($b_country);


        // Set the customer's Shipping Address
        $customerSAddress = new AnetAPI\CustomerAddressType();
        $customerSAddress->setFirstName($fName);
        $customerSAddress->setLastName($lName);
        //$customerAddress->setCompany("Souveniropolis");
        $customerSAddress->setAddress($s_address);
        $customerSAddress->setCity($s_city);
        $customerSAddress->setState($s_state);
        $customerSAddress->setZip($s_zip);
        $customerSAddress->setCountry($s_country);

        // Set the customer's identifying information
        $customerData = new AnetAPI\CustomerDataType();
        $customerData->setType("individual");
        $customerData->setId($user_id);
        $customerData->setEmail($user_email);

        // Create a TransactionRequestType object and add the previous objects to it
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        //$transactionRequestType->setTransactionType("authOnlyTransaction");
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        //$transactionRequestType->setShipping($amount);
        $transactionRequestType->setAmount(round($amount, 2));
        $transactionRequestType->setOrder($orderInfo);
        $transactionRequestType->setPayment($paymentOne);
        $transactionRequestType->setBillTo($customerAddress);
        $transactionRequestType->setShipTo($customerSAddress);
        $transactionRequestType->setCustomer($customerData);


        // Assemble the complete transaction request
        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setTransactionRequest($transactionRequestType);

        // Create the controller and get the response
        $controller = new AnetController\CreateTransactionController($request);
        //$response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        $response = $controller->executeWithApiResponse(config('services.authorize.endpoint'));

        $status_avs_hint = array("A" => 'Address (street) matches, ZIP code does not', 'B' => 'Address information not provided for AVS check',
            'E' => 'AVS error', 'G' => 'Non-U.S. card issuing bank', 'N' => 'No match on address (street) and ZIP code',
            'P' => 'AVS not applicable for this transaction', 'R' => 'Retry – System unavailable or timed out', 'S' => 'Service not supported by issuer',
            'U' => 'Address information is unavailable', 'W' => '9 digit ZIP code matches, address (street) does not','X'=>'Street address and 9-digit postal code match', 'Y' => 'Address (street) and 5 digit ZIP code match',
            'Z' => '5 digit ZIP matches, Address (Street) does not'
        );

        $status_cvv_hint = array('M' => 'Successful Match', 'N' => 'Does NOT Match', 'P'=> 'Is NOT Processed',
            'S' => 'Should be on card, but is not indicated', 'U' => 'Issuer is not certified or has not provided encryption key'
        );

        $transactionInfo = (object) array();

        // Log::error('authorize error : ' . json_encode($response));
        if ($response != null) {
            // Check to see if the API request was successfully received and acted upon
            if ($response->getMessages()->getResultCode()) {
                //pr($response);
                // Since the API request was successful, look for a transaction response
                // and parse it to display the results of authorizing the card
                $tresponse = $response->getTransactionResponse();

                if ($tresponse != null && $tresponse->getMessages() != null) {
                    $transactionInfo->status = 'Success';
                    $transactionInfo->message = date('m-d-Y H:i:s') . " Authorized and Captured with ID: " . $tresponse->getTransId();
                    $transactionInfo->transaction_code = $tresponse->getTransId();
                    $transactionInfo->transaction_response_code = $tresponse->getResponseCode();
                    $transactionInfo->authorized_time = date('Y-m-d H:i:s');
                    $transactionInfo->message_code = $tresponse->getMessages()[0]->getCode();
                    $transactionInfo->auth_code = $tresponse->getAuthCode();
                    $transactionInfo->avs_code = $tresponse->getAvsResultCode();
                    $transactionInfo->cvv_code = $tresponse->getCvvResultCode();
                    $transactionInfo->desc = $tresponse->getMessages()[0]->getDescription();
                    if($transactionInfo->avs_code){
                        $transactionInfo->avs_message = $status_avs_hint[$transactionInfo->avs_code];
                    }
                    if($transactionInfo->cvv_code){
                        $transactionInfo->cvv_message = $status_cvv_hint[$transactionInfo->cvv_code];
                    }
                } else {
                    $transactionInfo->status = 'Failed';
                    $transactionInfo->message = date('m-d-Y H:i:s') . ' Auth Failed';

                    if(!empty($tresponse) && $tresponse->getErrors() != null){
                        $transactionInfo->error_code = $tresponse->getErrors()[0]->getErrorCode();
                        $transactionInfo->error_message = $tresponse->getErrors()[0]->getErrorText();
                    }
                }
                // Or, print errors if the API request wasn't successful
            } else {
                $transactionInfo->status = 'Failed';
                $transactionInfo->message = date('m-d-Y H:i:s') . ' Auth Failed';
                $tresponse = $response->getTransactionResponse();

                if(!empty($tresponse) && $tresponse->getErrors() != null){
                    $transactionInfo->error_code = $tresponse->getErrors()[0]->getErrorCode();
                    $transactionInfo->error_message = $tresponse->getErrors()[0]->getErrorText();
                }
            }
        } else {
            $transactionInfo->status = 'Failed';
            $transactionInfo->message = 'No Response(Failed)';
        }

        $tInfo = json_encode($transactionInfo);

        DB::table('orders')->where('id', $orderId)->update(['authorize_info' => $tInfo,'aStatus'=>$transactionInfo->status]);

        //DB::table('orders')->where('id', $orderId)->update(['authorize_info' => $tInfo]);
        AuthorizeLog::create(['order_id' => $orderId,'authorize_type'=>'ShippingCharge',
            'authorize_info'=>$tInfo,'status'=>$transactionInfo->status,'responseCode'=>(isset($transactionInfo->transaction_response_code))?$transactionInfo->transaction_response_code:null,
            'authorize_message'=>(isset($transactionInfo->message_code))?$transactionInfo->message_code:null]);

        return $order;
    }

    public function storeNewBillingAddress($request, $user= null)
    {
        BuyerAddress::where('user_id', $user->id)->where('set_default', 'billing')->update(['set_default'=>null]);
        $address = new BuyerAddress();
        $address->location= $request['billingInput']['location'];
        $address->user_id= $user->id;
        $address->address = $request['billingInput']['address'];
        $address->unit = $request['billingInput']['unit'] ?? null;
        $address->city = $request['billingInput']['city'];
        $address->state_id = $request['billingInput']['state_id'];
        $address->state_text = isset($request['billingInput']['state_text']) ? $request['billingInput']['state_text'] : null;
        $address->zip = $request['billingInput']['zip'];
        $address->country_id = $request['billingInput']['country_id'];
        $address->first_name = $request['billingInput']['first_name'];
        $address->last_name = $request['billingInput']['last_name'];
        $address->phone = $request['billingInput']['phone'];
        $address->set_default = 'billing';
        $address->save();
    }

    public function storeNewShippingAddress($request, $user= null)
    {
        BuyerAddress::where('user_id', $user->id)->where('set_default', 'shipping')->update(['set_default'=>null]);
        $address = new BuyerAddress();
        $address->location= $request['shippingInput']['location'];
        $address->user_id= $user->id;
        $address->address = $request['shippingInput']['address'];
        $address->unit = $request['shippingInput']['unit'] ?? null;
        $address->city = $request['shippingInput']['city'];
        $address->state_id = $request['shippingInput']['state_id'];
        $address->state_text = isset($request['shippingInput']['state_text']) ? $request['shippingInput']['state_text'] : null;
        $address->zip = $request['shippingInput']['zip'];
        $address->country_id = $request['shippingInput']['country_id'];
        $address->first_name = $request['shippingInput']['first_name'];
        $address->last_name = $request['shippingInput']['last_name'];
        $address->phone = $request['shippingInput']['phone'];
        $address->set_default = 'shipping';
        $address->save();
    }

    public function sendOrderEmail(Request $request)
    {

        $request->validate([
            'order_number' => 'required|exists:orders,order_number',
        ]);



        $order = Order::where('order_number', '=', $request->order_number)->first();
        $requestUserId = $this->guard()->id();
        if($order->user_id != $requestUserId) return ;


        $pdfData = $this->getPdfData($order);


        // Send Mail to Buyer
        $order_time = Carbon::parse($order->created_at)->format('F d ,Y h:i:s A ');
        $orderItem = OrderItem::where('order_id', $order->id)->count('item_id');
        Mail::send('emails.buyer.order_confirmation', ['order' => $order, 'order_time' => $order_time, 'order_item' => $orderItem], function ($message) use ($order, $pdfData) {
            $message->subject('Order Confirmed');
            $message->to($order->email, $order->name);
            $message->attachData($pdfData, $order->order_number . '.pdf');
        });

        // Send Mail to Vendor
        $user = User::where('role', Role::$EMPLOYEE)->first();
        if(!$user) return;
        Mail::send('emails.vendor.new_order', ['order' => $order, 'order_time' => $order_time], function ($message) use ($order, $pdfData, $user) {
            $message->subject('New Order - '.$order->order_number);
            $message->to($user->email, $user->first_name.' '.$user->last_name);
            $message->attachData($pdfData, $order->order_number.'.pdf');
        });
    }
    public function checkOrder(Request $request)
    {
        $request->validate([
            'order_number' => 'required|exists:orders,order_number',
        ]);
        $id = Session::get('order_number');
        if(!$id)
            $id = $request->order_number;

        Session::forget('order_number');

        $order = Order::where('order_number', '=', $id)->first();
        $requestUserId = $this->guard()->id();
        if($order->user_id != $requestUserId)
            return response()->json(['message'=> 'unauthenticated'], 403);
        return response()->json(['message'=> 'yes and authenticate'], 200);
    }
    public function singleOrder(Request $request)
    {
        $request->validate([
            'order_number' => 'required|exists:orders,order_number',
        ]);

        $order = Order::where('order_number', '=', $request->order_number)->first();
        $requestUserId = $this->guard()->id();
        if($order->user_id != $requestUserId)
            return response()->json(['message'=> 'unauthenticated'], 403);
        $order->load('items', 'items.item', 'items.item.images', 'items.itemSize', 'items.statistic_color');

        foreach ($order->items as $item) {
            foreach ($item->item->images as $image) {
                $firstChar = $image->thumbs_image_path[0];

                if ($firstChar != '/')
                    $image->thumbs_image_path = '/'.$image->thumbs_image_path;
            }
        }
        return response()->json(['message'=> 'yes and authenticate', 'order' => $order], 200);
    }
    public function applyCoupon(Request $request) {
        $request->validate([
            'coupon' => 'required|exists:promotions,coupon_code',
        ]);

        $user_id = $this->guard()->id();
        $user_ip = $request->ip();
        if(!$user_id) {
            $user_id = $user_ip;
            $user = [$user_ip];
        } else {
            $user = [$user_id, $user_ip];
        }

        $coupon = \App\Model\Promotion::where('coupon_code', strtoupper(trim($request->coupon)))->where('status', 1)->first();
        // coupon exist or not.
        if (!$coupon)
            return response()->json(['errors' => ['coupon' => ['Invalid Coupon.']]], 422);

        // coupon multiple used or not.
        if ($coupon->multiple_use == 0) {
            $previousOrder = Order::where('user_id', $user_id)
                ->where('status', '!=', OrderStatus::$INIT)
                ->where('coupon', $coupon->coupon_code)
                ->first();

            if ($previousOrder)
                return response()->json(['errors' => ['coupon' => ['Coupon code has already been used once.']]], 422);
        }

        // for permanent coupon check expirity
        if($coupon->is_permanent == 0) {
            $startDate = strtotime($coupon->valid_from);
            $endDate = strtotime($coupon->valid_to);

            $currentTime = time();

            if($currentTime > $startDate && $currentTime < $endDate) {
                // valid
            } else {
                // not valid
                return response()->json(['success' => false, 'message' => 'Coupon Expired.']);
            }
        }


        $data = app('App\Http\Controllers\Api\CartController')->cartCouponDiscount($coupon, $user);
        $data['coupon_code'] = $coupon->coupon_code;
        $data['user_id'] = $user_id;
        $oldCartCoupon = CartCoupon::where('user_id', $user_id)->first();

        if ($oldCartCoupon) {
            // make logic for which one to take.
            $oldCartCoupon->update($data);
        } else {
            $cartCoupon = CartCoupon::create($data);
        }
        return response()->json(['success' => true, 'message' => 'Success.', 'coupon' => $data]);
    }
    public function removeCoupon(Request $request) {
        $user_id = $this->guard()->id();
        $user_ip = $request->ip();
        if(!$user_id) {
            $user_id = $user_ip;
            $user = [$user_ip];
        } else {
            $user = [$user_id, $user_ip];
        }

        $oldCartCoupon = CartCoupon::where('user_id', $user_id)
            ->first();

        if ($oldCartCoupon) {
            // remove if coupon exist
            $oldCartCoupon->delete();
        }
        return response()->json(['success' => true, 'message' => 'Success.']);
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
            $cardNumber = '12';
            $cardFullName = 'gh';
            $cardExpire = 'fg';
            $cardCvc = 'fg';
        } catch (DecryptException $e) {

        }

        $order->card_number = $cardNumber;
        $order->card_full_name = $cardFullName;
        $order->card_expire = $cardExpire;
        $order->card_cvc = $cardCvc;

        foreach($order->items as $item)
            $allItems[$item->item_id][] = $item;

        $logo_path = '';

        $vendor = MetaVendor::where('id', 1)->first();
        $order->vendor = $vendor;

        $black = DB::table('settings')->where('name', 'logo-black')->first();
        if ($black)
            $logo_path = public_path($black->value);

        $data = [
            'all_items' => [$allItems],
            'orders' => [$order],
            'logo_paths' => [$logo_path],
            'privacy_policy' => $vendor->order_notice
        ];

        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('admin.dashboard.orders.pdf.with_image', $data);
        return $pdf->output();
    }
    public function guard(){
        return Auth::Guard('api');
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
        $optimized_order = null;

        $coupons = Promotion::where('hasCouponCode', 0)->where('status', 1)->get();


        if(count($coupons) === 0) {
            $order = Order::where('id', $orderId)->first();
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

                $previous = Order::where('user_id', $order->user_id)
                    ->where('status', '!=', OrderStatus::$INIT)
                    ->where('default_coupon_id', $coupon->id)
                    ->first();

                if ($previous) {

                    $alreadyUsed = 1;

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
            $order->promotion_details = $discount > 0 ? $coupon_details : null;
            if($free_shipping === 1) {
                $order->total = $order->total - $order->shipping_cost;
                $order->shipping_cost = 0.00;
            }

            if($optimized_order == null) {
                $optimized_order = $order;
            }

            if($discount > $maximum_discount) {
                $maximum_discount = $discount;
                $optimized_order = $order;
            }

        }

        return $optimized_order;

    }
}
