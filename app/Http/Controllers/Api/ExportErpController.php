<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Model\Order;
use App\Enumeration\OrderStatus;
use Carbon\Carbon;
use Mail;
use DateTime;
use DB;
use App\Model\OrderItem;
use App\Model\Notification;

class ExportErpController extends Controller
{
    public function orders(Request $request)
    {
        if (!$this->checkAuth($request)) return response()->json(['error' => 'Unauthorized'], 401);
        if (!$this->checkPermission($request)) return response()->json(['error' => 'Permission Denied'], 403);

        $status_code = $request->status_code ?? 3; // by default confirmed orders.

        $orders = Order::where('status', $status_code)->orderBy('created_at','desc')->paginate(10);
        return $orders;
    }
    
    public function orderDetails(Request $request)
    {
        if (!$this->checkAuth($request)) return response()->json(['error' => 'Unauthorized'], 401);
        if (!$this->checkPermission($request)) return response()->json(['error' => 'Permission Denied'], 403);

        $orderId = $request->get('orderId');
        
        if (!$orderId) return response()->json(['error' => 'Undefined order'], 403);
        $order = Order::with('items', 'items.item', 'items.item.images', 'shippingMethod', 'storeCreditTransections', 'notifications')->find($orderId);
        $order->statusText = $order->statusText();
        return $order;
    }
    
    public function updateStatus(Request $request)
    {
        if (!$this->checkAuth($request)) return response()->json(['error' => 'Unauthorized'], 401);
        if (!$this->checkPermission($request)) return response()->json(['error' => 'Permission Denied'], 403);

        $orderId = $request->get('orderId');
        
        
        if (!$orderId) return response()->json(['error' => 'Undefined order'], 403);
        
        $order = Order::find($orderId);
        $order->status = $request->status;
        
        if ($order->status == OrderStatus::$CONFIRM_ORDER){
            $order->confirm_at = Carbon::now()->toDateTimeString(); 
        }else if ($order->status == OrderStatus::$PARTIALLY_SHIPPED_ORDER){
            $order->partially_shipped_at = Carbon::now()->toDateTimeString();
            $this->sendOrderStatusMail($order);
        }else if ($order->status == OrderStatus::$FULLY_SHIPPED_ORDER){
            $order->fully_shipped_at = Carbon::now()->toDateTimeString();
            $this->sendOrderStatusMail($order);
        }else if ($order->status == OrderStatus::$BACK_ORDER){
            $order->back_ordered_at = Carbon::now()->toDateTimeString();
            $this->sendOrderStatusMail($order);
        }else if (in_array($order->status, [OrderStatus::$CANCEL_BY_AGREEMENT, OrderStatus::$CANCEL_BY_BUYER, OrderStatus::$CANCEL_BY_VENDOR])){
            $order->cancel_at = Carbon::now()->toDateTimeString();
            $this->sendOrderStatusMail($order);
        }else if ($order->status == OrderStatus::$RETURNED){
            $order->return_at = Carbon::now()->toDateTimeString();
        }
        
        $order->touch();
        $order->save();
        
        return $order;
    }
    
    public function sendOrderStatusMail($order) {
        $order_time = Carbon::parse($order->created_at)->format('F d ,Y h:i:s A ');
        $orderItem = OrderItem::where('order_id', $order->id)->count('item_id');
        $settings = DB::table('settings')->where('name','logo-black')->first();
        //New Order
        if ($order->status == OrderStatus::$NEW_ORDER) {
            Mail::send('emails.buyer.order_confirmation', ['order' => $order, 'order_time' => $order_time, 'order_item' => $orderItem, 'settings'=> $settings], function ($message) use ($order) {
                $message->subject('New Order');
                $message->to($order->email, $order->name);
            }); 
        }

        // Send Notification
        if ($order->status == OrderStatus::$BACK_ORDER) {
            Notification::create([
                'user_id' => $order->user_id,
                'text' => $order->order_number." need approval.",
                'link' => route('show_order_details', ['order' => $order->id]),
                'view' => 0
            ]);

            $order->rejected = 0;
            $order->save();

            // Send Mail to Buyer
            Mail::send('emails.buyer.back_order', ['order' => $order, 'order_time' => $order_time, 'order_item' => $orderItem, 'settings'=> $settings], function ($message) use ($order) {
                $message->subject('Back Order');
                $message->to($order->email, $order->name);
            }); 
        }

        //Confirm Order
        if ($order->status == OrderStatus::$CONFIRM_ORDER) {
            // Send Mail to Buyer
             Mail::send('emails.buyer.order_confirmed', ['order' => $order, 'order_time' => $order_time, 'order_item' => $orderItem, 'settings'=> $settings], function ($message) use ($order) {
                 $message->subject('Order Confirmation');
                 $message->to($order->email, $order->name);
             }); 
              
         }

         //Partially Shipped Order
        if ($order->status == OrderStatus::$PARTIALLY_SHIPPED_ORDER) {
            // Send Mail to Buyer
             Mail::send('emails.buyer.partially_shipped_order', ['order' => $order, 'order_time' => $order_time, 'order_item' => $orderItem, 'settings'=> $settings], function ($message) use ($order) {
                 $message->subject('Partially Shipped Order');
                 $message->to($order->email, $order->name);
             }); 
              
         }
         //Fully Shipped Order
         if ($order->status == OrderStatus::$FULLY_SHIPPED_ORDER) {
            // Send Mail to Buyer
             Mail::send('emails.buyer.order_status_shipped', ['order' => $order, 'order_time' => $order_time, 'order_item' => $orderItem, 'settings'=> $settings], function ($message) use ($order) {
                 $message->subject('Shipping Confirmation');
                 $message->to($order->email, $order->name);
             }); 
              
         }
         
         //Cancelled Order
         if ($order->status == OrderStatus::$CANCEL_BY_AGREEMENT) {
            // Send Mail to Buyer
             Mail::send('emails.buyer.order_status_cancel', ['order' => $order, 'order_time' => $order_time, 'order_item' => $orderItem, 'settings'=> $settings], function ($message) use ($order) {
                 $message->subject('Cancelled Order');
                 $message->to($order->email, $order->name);
             });  
         }
         
         if ($order->status == OrderStatus::$CANCEL_BY_BUYER) {
            // Send Mail to Buyer
             Mail::send('emails.buyer.order_status_cancel', ['order' => $order, 'order_time' => $order_time, 'order_item' => $orderItem, 'settings'=> $settings], function ($message) use ($order) {
                 $message->subject('Cancelled Order');
                 $message->to($order->email, $order->name);
             }); 
              
         }
         
         if ($order->status == OrderStatus::$CANCEL_BY_VENDOR) {
            // Send Mail to Buyer
             Mail::send('emails.buyer.order_status_cancel', ['order' => $order, 'order_time' => $order_time, 'order_item' => $orderItem, 'settings'=> $settings], function ($message) use ($order) {
                 $message->subject('Cancelled Order');
                 $message->to($order->email, $order->name);
             });  
         }
    }
    
    private function checkAuth($request)
    {
        $credentials = request(['user_name', 'password']);
        return Auth::attempt($credentials);
    }
    private function checkPermission($request)
    {
        $user = \App\Model\User::where('user_name', '=', $request->user_name)->first();
        if (!$user) return false;
        if ($user->role == 1) return true;
        return false;
    }
}
