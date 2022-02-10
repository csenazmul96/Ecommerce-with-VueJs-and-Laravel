<?php

namespace App\Http\Controllers;

use App\Enumeration\OrderStatus;
use App\Model\BuyerAddress;
use App\Model\CartCoupon;
use App\Model\CartItem;
use App\Model\Country;
use App\Model\ItemInv;
use App\Model\MetaBuyer;
use App\Model\Order;
use App\Model\OrderItem;
use App\Model\State;
use App\Model\Statistic;
use App\Model\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ShippingAddress;
use PayPal\Api\ShippingCost;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use URL;
use Illuminate\Support\Facades\Input;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use Session;
use Redirect;
use Mail;
use DB;

class PayPalController extends Controller
{
    public function __construct()
    {
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
                config('paypal.client_id'),
                config('paypal.secret')
        ));
        $this->_api_context->setConfig(config('paypal.settings'));
    }

    public function payment(Request $request)
    {

        $shippingInput = json_decode($request->shippingInput);
        $billingInput = json_decode($request->billingInput);
        $payer = new Payer();
        $amount = new Amount();
        $amountDetails = new Details();
        $shipping_address = new ShippingAddress();
        $itemList = new ItemList();
        $transaction = new Transaction();

        $shipping_address->setCity($shippingInput->city);
        $shipping_address->setCountryCode($shippingInput->location);
        $shipping_address->setPostalCode($shippingInput->zip);
        $shipping_address->setLine1($shippingInput->address);
        $shipping_address->setState($shippingInput->state ? $shippingInput->state->code : $shippingInput->state_id);
        $shipping_address->setRecipientName($shippingInput->first_name .' '. $shippingInput->last_name);

        $user = User::where('email', $request->email)->first();
        if(!$user){
            $user = $this->register($request, $shippingInput, $billingInput);
        }
        $shipppingMethod = json_decode($request->shippingMethod);

        if($billingInput->id === '-1'){
            $this->storeNewBillingAddress($billingInput, $user);
        }
        if($shippingInput->id === '-1'){
            $this->storeNewShippingAddress($shippingInput, $user);
        }
        $bstate = State::where('id', $billingInput->state_id)->first();
        $bcountry = Country::where('id', $billingInput->country_id)->first();
        $sstate = State::where('id', $shippingInput->state_id)->first();
        $scountry = Country::where('id', $shippingInput->country_id)->first();
        $freeShipping = $request->freeShipping ? 1 : 0;
        $orderData = [
            'status' => OrderStatus::$NEW_ORDER,
            'note' =>  $request['note'],
            'user_id' => $user->id,
            'company_name' => ($user && $user->buyer) ? $user->buyer->company_name : '',
            'name' => $request->first_name .' '.$request->last_name,
            'email' => $request->email,
            'can_call' => 0,
            'b_user_name' => $billingInput->first_name .' '.$billingInput->last_name,
            'billing_location' => $billingInput->location,
            'billing_address' => $billingInput->address,
            'billing_city' => $billingInput->city,
            'billing_state' => $bstate ? $bstate->name : NULL,
            'billing_state_id' => $billingInput->state_id,
            'billing_state_text' =>  $billingInput->state_text,
            'billing_zip' => $billingInput->zip,
            'billing_country' => $bcountry? $bcountry->name : NULL,
            'billing_country_id' => $billingInput->country_id,
            'billing_phone' => $billingInput->phone,
            'payment_type' => 'Paypal',

            's_user_name' => $shippingInput->first_name .' '.$shippingInput->last_name,
            'shipping_location' => $shippingInput->location,
            'shipping_address' => $shippingInput->address,
            'shipping_city' => $shippingInput->city,
            'shipping_state' => $sstate ? $sstate->name : NULL,
            'shipping_state_id' => $shippingInput->state_id,
            'shipping_state_text' => $shippingInput->state_text,
            'shipping_zip' => $shippingInput->zip,
            'shipping_country' => $scountry? $scountry->name : NULL,
            'shipping_country_id' => $shippingInput->country_id,
            'shipping_phone' => $shippingInput->phone,
        ];
        $orderData['shipping_method_id'] = $shipppingMethod ? $shipppingMethod->id : NULL;
        $orderData['shipping'] = $shipppingMethod ? $shipppingMethod->name : NULL;
        $orderData['shipping_cost'] = $freeShipping == 1 ? 0.00 : $request->shippingCost;
        $orderData['discount'] = $request->discount;
        $orderData['total'] = $request->totalAmount;


        $itemData = [];
        $subTotal = 0;
        foreach (json_decode($request->cartItems) as $cartItem){
            $item_1 = new Item();
            $item_1->setName($cartItem->item ? $cartItem->item->name : ' ')->setCurrency('USD')->setQuantity($cartItem->quantity)->setPrice($cartItem->item ? number_format($cartItem->item->price, 2, '.', '')  : 0);
            $itemData[] = $item_1;
        }
        if(isset($request->discount) && $request->discount > 0 ){
            $index = sizeof($itemData);
            $item_1 = new Item();
            $item_1->setName('Discount')->setCurrency('USD')->setQuantity(1)->setPrice(number_format( - $request->discount, 2, '.', ''));
            $itemData[$index+1] =$item_1;
        }

        $total = number_format($request->totalAmount , 2, '.', '');
        $subtotal = number_format($request->cartTotal - $request->discount , 2, '.', '');
        $orderData['subtotal'] = $subtotal;
        $itemList->setItems($itemData);
        $itemList->setShippingAddress($shipping_address);
        $payer->setPaymentMethod('paypal');
        $amountDetails->setShipping($freeShipping == 1 ? 0.00 : $request->shippingCost);

        $amountDetails->setSubtotal($subtotal);
        $amount->setCurrency('USD')->setDetails($amountDetails)->setTotal($total);

        $transaction->setAmount($amount);
        $transaction->setItemList($itemList);
        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::to('paypal-status'))->setCancelUrl(URL::to('checkout'));

        $payment = new Payment();
        $payment->setIntent('Sale')->setPayer($payer)->setRedirectUrls($redirect_urls)->setTransactions(array($transaction));

        try {
            $payment->create($this->_api_context);

            $order = Order::create($orderData);
            $coupon = app('App\Http\Controllers\Api\CartController')->getCoupon($user->id, [$user->id], true);
            $order->free_shipping = $freeShipping;
            if (!$coupon) {
                $order->discount = 0.00;
                $order->promotion_details = '';
                $order->coupon = '';
            } else {
//                $order->subtotal = number_format($subTotal, 2, '.', '');
                $order->discount = number_format($coupon->discount, 2, '.', '');
                $order->promotion_details = $coupon->promotion_details;
                $order->coupon = $coupon->coupon_code;
                if ($freeShipping == 1) {
                    $order->shipping_cost = 0.00;
                } else {
                    $order->shipping_cost = $request['shippingMethod']['fee'];
                }
                CartCoupon::where('user_id', $user->id)->delete();
            }

            $orderCount = Order::count();
            $order->order_number = 'HG'.str_pad($orderCount+1, 6, "0", STR_PAD_LEFT);
            $order->status = 0;
            $order->save();

            foreach (json_decode($request->cartItems) as $cartItem){
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

                $itemInventory = ItemInv::where('item_id', $cartItem->item_id)->where('color_id', $cartItem->color_id)->where('itemsize', $cartItem->size_id)->first();
                if(isset($itemInventory)){
                    ItemInv::where('item_id', $cartItem->item_id)->where('color_id', $cartItem->color_id)->where('itemsize', $cartItem->size_id)->update([
                        'qty' => $itemInventory->qty - $cartItem->quantity
                    ]);
                }
            }

        } catch (\Exception $ex) {
            return Redirect::to('/checkout?error=Addressing');
        }

        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }

        /** add payment ID to session **/
        Session::put('paypal_payment_id', $payment->getId());
        Session::put('paypal_user_order_id', $order->id);

        if (isset($redirect_url)) {
            /** redirect to paypal **/
            return Redirect::away($redirect_url);

        }
    }

    public function getPaymentStatus()
    {
        /** Get the payment ID before session clear **/
        $payment_id = Session::get('paypal_payment_id');
        $paypal_user_order_id = Session::get('paypal_user_order_id');
        $order = Order::with('items')->where('id', $paypal_user_order_id)->first();
        /** clear the session payment ID **/
        Session::forget('paypal_payment_id');
        if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {

            \Session::put('error', 'Payment failed');
            return Redirect::to('/');

        }
        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId(Input::get('PayerID'));

        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);

        if ($result) {
            if($order){
                CartItem::where('user_id', $order->user_id)->delete();
                $order->paypal_payment_id = $result->getId();
                $order->paypal_payer_id = $result->getPayer()->getPayerInfo()->getPayerId();
                $order->paypal_token = $result->getToken();
                $order->aStatus = $result->getState();
                $order->paypal_autho_time = $result->getCreateTime();
                $order->paypal_links = $result->getRedirectUrls()->getReturnUrl();
                $order->status = 2;
                $order->save();

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
                Mail::send('emails.buyer.new_order', ['order' => $order, 'order_time' => $order_time, 'order_item' => $orderItem, 'logo'=>$black_logo_path], function ($message) use ($order) {
                    $message->subject('Order Received');
                    $message->to(env('MAIL_FROM_ADDRESS'), $order->name);
                });
                Session::put('order_number', $order->order_number);
            }
        }

        \Session::put('error', 'Payment failed');
        return Redirect::to('/order-complete?order_id='.$order->order_number);

    }

    public function paymentCaptcha(Request $request)
    {
        $order = Order::find($request->id);
    }
    public function register($request, $shippingInput, $billingInput){
        $metaBuyer = MetaBuyer::create([
            'verified' => 0,
            'active' => 0,
            'user_id' => 0,
            'shipping_location' => $shippingInput->location,
            'shipping_address' => $shippingInput->address,
            'shipping_city' => $shippingInput->city,
            'shipping_state_id' => $shippingInput->state_id,
            'shipping_state' => $shippingInput->state_text,
            'shipping_zip' => $shippingInput->zip,
            'shipping_country_id' => $shippingInput->country_id,
            'shipping_phone' => $shippingInput->phone,
            'billing_location' => $billingInput->location,
            'billing_address' => $billingInput->address,
            'billing_city' => $billingInput->city,
            'billing_state_id' => $billingInput->state_id,
            'billing_state' => $billingInput->state_text,
            'billing_zip' => $billingInput->zip,
            'billing_country_id' => $billingInput->country_id,
            'billing_phone' => $billingInput->phone,
            'receive_offers' => 1,
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'email' => $request->email,
            'active' => 0,
            'last_name' => $request->last_name,
            'password' => bcrypt($request->password),
            'role' => \App\Enumeration\Role::$BUYER,
            'buyer_meta_id' => $metaBuyer->id,
        ]);
        $metaBuyer->user_id = $user->id;
        $metaBuyer->save();

        return $user->load('buyer');
    }

    public function storeNewBillingAddress($request, $user= null)
    {
        BuyerAddress::where('user_id', $user->id)->where('set_default', 'billing')->update(['set_default'=>null]);
        $address = new BuyerAddress();
        $address->location= $request->location;
        $address->first_name = $request->first_name;
        $address->last_name = $request->last_name;
        $address->address = $request->address;
        $address->city = $request->city;
        $address->user_id= $user->id;
        $address->state_id = $request->state_id;
        $address->state_text = $request->state_text;
        $address->zip = $request->zip;
        $address->country_id = $request->country_id;
        $address->phone = $request->phone;
        $address->set_default = 'billing';
        $address->save();

    }

    public function storeNewShippingAddress($request, $user= null)
    {
        BuyerAddress::where('user_id', $user->id)->where('set_default', 'shipping')->update(['set_default'=>null]);
        $address = new BuyerAddress();
        $address->location= $request->location;
        $address->first_name = $request->first_name;
        $address->last_name = $request->last_name;
        $address->address = $request->address;
        $address->city = $request->city;
        $address->user_id= $user->id;
        $address->state_id = $request->state_id;
        $address->state_text = $request->state_text;
        $address->zip = $request->zip;
        $address->country_id = $request->country_id;
        $address->phone = $request->phone;
        $address->set_default = 'shipping';
        $address->save();
    }
}
