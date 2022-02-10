<?php

namespace App\Http\Controllers\Admin;

use App\Enumeration\OrderStatus;
use App\Model\ItemImages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Order;
use Carbon\Carbon;
use DateTime;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;

class ShipStationController extends Controller
{
    public function index()
    {
        $orders = [];
        $apiKey = env('SHIP_STATION_KEY');
        $apiSecret = env('SHIP_STATION_SECRET');
        $auth = base64_encode($apiKey . ":" . $apiSecret);

        $client = new Client(['verify' => false]);
        $headers = [
            'Host' => 'ssapi.shipstation.com',
            'Authorization' => 'Basic '.$auth,
        ];

        try {
            $res = $client->delete('https://ssapi.shipstation.com/orders/34278804', [
                'headers' => $headers,
            ]);

            $res = json_decode($res->getBody(), true);

        } catch (\BadResponseException $e) {
            return response()->json(['success' => false, 'message' => 'Color creation error!.']);
        }



    }
    public function order(Request $request)
    {

        $apiKey = env('SHIP_STATION_KEY');
        $apiSecret = env('SHIP_STATION_SECRET');
        $auth = base64_encode($apiKey . ":" . $apiSecret);

        $client = new Client(['verify' => false]);
        $headers = [
            'Host' => 'ssapi.shipstation.com',
            'Authorization' => 'Basic '.$auth,
            'Accept' => 'application/json',
            'content-type' => 'application/json'
        ];
        $bodyData = [];
        $orders = Order::where('shipstation_sync', null)->with('items')->where('status', '!=', 0)->get();
        $counter = count($orders);
        // dd($orders);
        if($counter > 0){
            $i = 0;
            foreach ($orders as $order){
                $status = 'awaiting_shipment';
                if(($order->status ===  OrderStatus::$PARTIALLY_SHIPPED_ORDER) || ($order->status ===  OrderStatus::$FULLY_SHIPPED_ORDER))
                    $status = 'shipped';
                if(($order->status ===  OrderStatus::$CANCEL_BY_BUYER) || ($order->status ===  OrderStatus::$CANCEL_BY_VENDOR) || ($order->status ===  OrderStatus::$CANCEL_BY_AGREEMENT))
                    $status = 'cancelled';
                if(($order->status ===  OrderStatus::$BACK_ORDER) || ($order->status ===  OrderStatus::$RETURNED))
                    $status = 'on_hold';
                if(($order->astatus ===  'Failed'))
                    $status = 'awaiting_payment';

                $orderitems = [];
                $j=0;
                foreach ($order->items as $item){
                    $image = ItemImages::where('item_id', $item->item_id)->where('sort', 1)->first();
                    $orderitems[$j]=[
                        'sku'=> $item->item->style_no ?? NULL,
                        'name'=> $item->item->name ?? null,
                        'quantity'=> $item->qty ?? null,
                        'unitPrice'=> $item->per_unit_price ?? null,
                        'productId'=> $item->item->id ?? null,
                        'imageUrl'=> Storage::url($image->compressed_image_jpg_path) ?? null,
                    ];
                    $j++;
                }

                $date = new DateTime($order->created_at);
                $orderDate = $date->format('Y-m-d H:i:s');
                $billTo = [
                    "name"=> $order->s_user_name,
                    "company"=> null,
                    "street1"=> $order->shipping_address,
                    "city"=> $order->shipping_city,
                    "state"=> $order->shipping_state ?? $order->shipping_state_text,
                    "postalCode"=> $order->shipping_zip,
                    "country"=> $order->shipping_location,
                    "phone"=> $order->shipping_phone,
                ];

                $shipTo = [
                    "name"=> $order->b_user_name,
                    "street1"=> $order->billing_address,
                    "city"=> $order->khulna,
                    "state"=> $order->billing_state ?? $order->billing_state_text,
                    "postalCode"=> $order->billing_zip,
                    "country"=> $order->billing_location,
                    "phone"=> $order->billing_phone,
                ];

                $bodyData[$i]['orderNumber'] = $order->order_number;
                $bodyData[$i]['orderDate'] = $orderDate;
                $bodyData[$i]['orderStatus'] = $status;
                $bodyData[$i]['billTo'] = $billTo;
                $bodyData[$i]['shipTo'] = $shipTo;
                $bodyData[$i]['items'] = $orderitems;
                $i++;
            }

            $bodyData = json_encode($bodyData, true);


            try {
                $res = $client->post('https://ssapi.shipstation.com/orders/createorders', [
                    'headers' => $headers,
                    'body' => $bodyData
                ]);

                $res = json_decode($res->getBody(), true);

                foreach ($orders as &$order){
                    $order->shipstation_sync = true;
                    $order->save();
                }
                return response()->json(['success' => true, 'message' => 'Orders Sync Successfull', 'result'=> $res['results']]);
            } catch (\BadResponseException $e) {
                return response()->json(['success' => false, 'message' => 'Order Sync error!.', 'result'=> $res['results']]);
            }
        }else{
            return response()->json(['success' => true, 'message' => 'No order available out of sync', 'result'=> '']);
        }
    }
}
