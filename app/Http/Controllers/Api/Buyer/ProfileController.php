<?php

namespace App\Http\Controllers\Api\Buyer;

use DB;
use Uuid;
use App\Model\User;
use App\Model\Order;
use App\Model\State;
use App\Model\Country;
use App\Model\Setting;
use Validator;
use App\Model\Size;
use App\Model\ItemImages;
use App\Model\Color;
use App\Model\MetaBuyer;
use App\Model\MetaVendor;
use App\Model\StoreCredit;
use App\Model\AdminMessage;
use App\Model\BuyerMessage;
use App\Model\WishListItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Enumeration\OrderStatus;
use App\Model\BuyerAddress;
use App\Model\Newsletter;
use App\Enumeration\VendorImageType;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
    public function index(){
        $user = Auth::user();
        $user->load('buyer');
        $storecredit = StoreCredit::where('user_id', Auth::user()->id)->first();
        $user->storecredit = $storecredit;
        $defaultBilling = BuyerAddress::with('state', 'country')
                                ->where('user_id', Auth::user()->id)
                                ->where('set_default', 'LIKE', '%billing%')
                                ->first();
        $defaultShipping = BuyerAddress::with('state', 'country')
                                ->where('user_id', Auth::user()->id)
                                ->where('set_default', 'LIKE', '%shipping%')
                                ->first();
        $newsletter = Newsletter::where('email', Auth::user()->email)
                                ->first();

        if(!$defaultShipping){
            $defaultShipping = BuyerAddress::with('state', 'country')
                ->where('user_id', Auth::user()->id)
                ->first();
        }
        if(!$defaultBilling){
            $defaultBilling = BuyerAddress::with('state', 'country')
                ->where('user_id', Auth::user()->id)
                ->first();
        }

        return response()->json(['user' => $user, 'defaultBilling'=> $defaultBilling, 'defaultShipping'=> $defaultShipping, 'newsletter' => $newsletter], 200);
    }
    public function address(Request $request) {
        $user = Auth::user();
        $user->load('buyer');
        $metabuyer = MetaBuyer::where('id', Auth::user()->buyer_meta_id)->with('user')->first();
        $usStates = State::where('country_id', 1)->orderBy('name')->get()->toArray();
        if ($request->manage && $request->manage == 'customer'){
            $addresses = BuyerAddress::where('user_id', Auth::user()->id) ->with('state', 'country')->orderBy('updated_at', 'desc')->paginate(10);
        } else {
            $addresses['data'] = BuyerAddress::where('user_id', Auth::user()->id) ->with('state', 'country')->orderBy('updated_at', 'desc')->get();
        }
        return response()->json(['user' => $user,'metabuyer'=> $metabuyer,'usStates'=> $usStates,'addresses'=> $addresses], 200);
    }
    public function removeAddress(Request $request){
        $request->validate([
            'address_id' => 'required|exists:buyer_addresses,id',
        ]);

        $address = BuyerAddress::where('user_id', $request->user()->id)
                                ->where('id', $request->address_id)
                                ->first();

        if ($address) {
            $address->delete();
        }

        return response()->json(['message' => 'Your request is successfull.'], 200);
    }
    public function defaultAddress(Request $request){
        // on set default address. change it also from meta buyer
        $request->validate([
            'address_id' => 'required|exists:buyer_addresses,id',
            'set_default' => 'required|in:billing,shipping',
        ]);
        $set_default = $request->set_default;
        switch ($set_default) {
            case 'billing':
                $defaultBillings = BuyerAddress::where('user_id', $request->user()->id)
                                    ->where('set_default', 'LIKE', '%billing%')
                                    ->get();
                foreach ($defaultBillings as $defaultBilling) {
                    $old_default = $defaultBilling->set_default;
                    $new_default = $old_default;
                    if (strpos($old_default, 'billing') !== false) {
                        $new_default = str_replace("billing", "", $old_default);
                    }
                    $defaultBilling->set_default = $new_default;
                    $defaultBilling->save();
                }

                $billingAddress = BuyerAddress::where('user_id', $request->user()->id)
                                    ->where('id', $request->address_id)
                                    ->first();
                $billingAddress->set_default = $billingAddress->set_default.',billing';
                $billingAddress->save();

                $state = 'INT';
                if($billingAddress->location === "CA")
                    $state = 'CA';
                if($billingAddress->location === "US")
                    $state = 'US';

                $buyer = MetaBuyer::where('user_id', $request->user()->id)->first();
                $buyer->billing_location = $state;
                $buyer->billing_address = $billingAddress->address;
                $buyer->billing_city = $billingAddress->city;
                $buyer->billing_state_id = $billingAddress->state_id;
                $buyer->billing_state = $billingAddress->state_text;
                $buyer->billing_unit = $billingAddress->city;
                $buyer->billing_zip = $billingAddress->zip;
                $buyer->billing_country_id = $billingAddress->country_id;
                $buyer->billing_phone = $billingAddress->phone;
                $buyer->save();

                break;
            case 'shipping':
                $defaultShippings = BuyerAddress::where('user_id', $request->user()->id)
                                    ->where('set_default', 'LIKE', '%shipping%')
                                    ->get();
                foreach ($defaultShippings as $defaultShipping) {
                    $old_default = $defaultShipping->set_default;
                    $new_default = $old_default;
                    if (strpos($old_default, 'shipping') !== false) {
                        $new_default = str_replace("shipping", "", $old_default);
                    }
                    $defaultShipping->set_default = $new_default;
                    $defaultShipping->save();
                }
                $shippingAddress = BuyerAddress::where('user_id', $request->user()->id)
                                    ->where('id', $request->address_id)
                                    ->first();
                $shippingAddress->set_default = $shippingAddress->set_default.',shipping';
                $shippingAddress->save();
                $state = 'INT';
                if($shippingAddress->location === "CA")
                    $state = 'CA';
                if($shippingAddress->location === "US")
                    $state = 'US';

                $buyer = MetaBuyer::where('user_id', $request->user()->id)->first();
                $buyer->shipping_location = $state;
                $buyer->shipping_address = $shippingAddress->address;
                $buyer->shipping_city = $shippingAddress->city;
                $buyer->shipping_state_id = $shippingAddress->state_id;
                $buyer->shipping_state = $shippingAddress->state_text;
                $buyer->shipping_unit = $shippingAddress->city;
                $buyer->shipping_zip = $shippingAddress->zip;
                $buyer->shipping_country_id = $shippingAddress->country_id;
                $buyer->shipping_phone = $shippingAddress->phone;
                $buyer->save();

                break;
        }
        return response()->json(['message' => 'Your request is successfull.'], 200);
    }
    public function addAddress(Request $request){
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'nullable|string',
            'store_no' => 'nullable|string',
            'address' => 'required|string',
            'address2' => 'nullable|string',
            'city' => 'required|string',
            'phone' => 'required|string',
            'zip' => 'required|string',
            'fax' => 'nullable|string',
            'location' => 'required|string',
            'id' => 'nullable|numeric|exists:buyer_addresses,id',
            'state_id' => 'nullable|numeric|exists:states,id',
            'country_id' => 'required|numeric|exists:countries,id',
            'set_default' => 'required|string|in:billing,shipping',
        ]);
        if($request->set_default === 'shipping') {
            BuyerAddress::where('user_id', $request->user()->id)->where('set_default', 'LIKE', '%shipping%')->update(['set_default'=> null]);
        }
        if($request->set_default === 'billing') {
            BuyerAddress::where('user_id', $request->user()->id)->where('set_default', 'LIKE', '%billing%')->update(['set_default'=> null]);
        }

        $state = 'INT';
        if($request->location === "CA")
            $state = 'CA';
        if($request->location === "US")
            $state = 'US';

        $address = BuyerAddress::create([
            'user_id' => $request->user()->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'location' => $state,
            'address' => $request->address,
            'address2' => $request->address2,
            'city' => $request->city,
            'state_id' => $request->state_id,
            'state_text' => $request->state_text,
            'zip' => $request->zip,
            'country_id' => $request->country_id,
            'phone' => $request->phone,
            'set_default' => $request->set_default,
        ]);

        $buyer = MetaBuyer::where('user_id', $request->user()->id)->first();
        if($request->set_default === 'shipping') {
            $buyer->shipping_location = $state;
            $buyer->shipping_address = $request->address;
            $buyer->shipping_city = $request->city;
            $buyer->shipping_state_id = $request->state_id;
            $buyer->shipping_state = $request->state_text;
            $buyer->shipping_unit = $request->city;
            $buyer->shipping_zip = $request->zip;
            $buyer->shipping_country_id = $request->country_id;
            $buyer->shipping_phone = $request->phone;
            $buyer->save();
        }
        if($request->set_default === 'billing') {
            $buyer->billing_location = $state;
            $buyer->billing_address = $request->address;
            $buyer->billing_city = $request->city;
            $buyer->billing_state_id = $request->state_id;
            $buyer->billing_state = $request->state_text;
            $buyer->billing_unit = $request->city;
            $buyer->billing_zip = $request->zip;
            $buyer->billing_country_id = $request->country_id;
            $buyer->billing_phone = $request->phone;
            $buyer->save();
        }

        return response()->json(['result' => 'success'], 200);
    }
    public function updateAddress(Request $request){
            $request->validate([
                'first_name' => 'required|string',
                'last_name' => 'nullable|string',
                'store_no' => 'nullable|string',
                'address' => 'required|string',
                'address2' => 'nullable|string',
                'city' => 'required|string',
                'phone' => 'required|string',
                'zip' => 'required|string',
                'fax' => 'nullable|string',
                'location' => 'required|string',
                'id' => 'nullable|numeric|exists:buyer_addresses,id',
                'state_id' => 'nullable|numeric|exists:states,id',
                'country_id' => 'required|numeric|exists:countries,id',
                'set_default' => 'required|string|in:billing,shipping',
            ]);

        if($request->set_default === 'shipping') {
            BuyerAddress::where('user_id', $request->user()->id)->where('set_default', 'LIKE', '%shipping%')->update(['set_default'=> null]);
        }
        if($request->set_default === 'billing') {
            BuyerAddress::where('user_id', $request->user()->id)->where('set_default', 'LIKE', '%billing%')->update(['set_default'=> null]);
        }

        $state = $request->state_text;
        $state_id = $request->state_id;
        $location = 'INT';
        if($request->country_id === '1')
            $location = 'US';
        if($request->country_id === '2')
            $location = 'CA';

        $BuyerAddress = BuyerAddress::where('id', $request->id)->first();
        if($BuyerAddress) {
            $BuyerAddress->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'location' => $location,
                'address' => $request->address,
                'address2' => $request->address2,
                'city' => $request->city,
                'state_id' => $state_id,
                'state_text' => $state,
                'zip' => $request->zip,
                'country_id' => $request->country_id,
                'phone' => $request->phone,
                'set_default' => $request->set_default,
            ]);
        }
        return response()->json(['result' => 'success'], 200);
    }
    public function orders(Request $request){
        $unread_messages = 0;
        if(Auth::user()){
            $unread_messages = BuyerMessage::where('user_id', Auth::user()->id)
                ->where('reading_status', 0)
                ->count();
        }
        $orders = Order::where('status', '!=', OrderStatus::$INIT)->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(10);
        return response()->json(['orders' => $orders, 'unread_messages'=> $unread_messages], 200);
    }
    public function messages(Request $request){
        $unread_messages = 0;
        $messages = BuyerMessage::where('user_id', Auth::user()->id)->orderBy('created_at', 'asc')->paginate(10);
        if(Auth::user()){
            $unread_messages = BuyerMessage::where('user_id', Auth::user()->id)->where('reading_status', 0)->count();
        }
        $data=[
            'allmessage' => $messages,
            'unread_messages' => $unread_messages,
        ];
        return response()->json(['messages' => $data], 200);
    }
    public function messagesStatusChange(Request $request){
        $message = BuyerMessage::where('id',$request->msgId)->first();
        $message->reading_status = 0;
        $message->save();
        return response()->json([], 200);
    }
    public function sendMessageReplay(Request $request){
        return $request->all();
        $request->validate([
            'message' => 'required',
            'sender' => 'required',
            'subject' => 'required',
        ]);

        $attachment1 = null;
        $attachment2 = null;
        $attachment3 = null;

        if (count($request->attachment) > 2)
            $attachment3 = $request->attachment[2];

        if (count($request->attachment) > 1)
            $attachment2 = $request->attachment[1];

        if (count($request->attachment) > 0)
            $attachment1 = $request->attachment[0];

        AdminMessage::create([
            'user_id' => $request->user_id,
            'sender' => $request->recipient,
            'recipient' => $request->sender,
            'subject' => $request->subject,
            'message' => $request->message,
            'attachment1' => $attachment1,
            'attachment2' => $attachment2,
            'attachment3' => $attachment3,
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
        return response()->json([], 200);
    }
    public function attachmentUpload( $attachment = null)
    {
        $filename = Uuid::generate()->string;
        $file = $attachment;
        $ext = $file->getClientOriginalExtension();

        $destinationPath = '/buyer_message_attachment';
        $file->move(public_path($destinationPath), $filename.".".$ext);
        $imagePath = $filename.".".$ext;

        return $imagePath;
    }
    public function sendMessageDownlaod(Request $request)
    {

        $filename = $request->attachment1;
    }
    public function OrderDetails($id){
        $order = Order::find($id);
        $allItems = [];
        $order->load( 'items');
        $vendor = MetaVendor::where('id', 1)->first();
        $order->vendor = $vendor;
        // Logo
        $default_img = Setting::where('name', 'default-item-image')->first();
        $vendor_logo_path = '';
        $black = \DB::table('settings')->where('name', 'logo-black')->first();
        if ($black)
            $vendor_logo_path = asset($black->value);
        $i = 0;
        foreach($order->items as $item){
            $id = $item->item_id;
            if(!empty($item->color)){
                $item->image = ItemImages::where('item_id',$id)->where('color_id',$item->color)->first();
            }else{
                $item->image = ItemImages::where('item_id',$id)->first();
            }
            $allItems[$i] = $item;
            if(!empty($item->size)){
                $allItems[$i]['sizes'] = Size::where('id',$item->size)->first();
            }else{
                $allItems[$i]['sizes'] = '';

            }
            if(!empty($item->color)){
                $allItems[$i]['colors'] = Color::where('id',$item->color)->first();
            }else{
                $allItems[$i]['colors'] = '';

            }
            $i++;
        }

        return response()->json(['orders' => $order, 'allItems'=> $allItems,'default_img' =>$default_img, 'vendor_logo_path' => $vendor_logo_path], 200);
    }
    public function billingaddresspost(Request $request){
        $rules['factoryAddress']= 'required';
        $rules['factoryCity']= 'required';
        $rules['factoryState']= 'required_without_all:factoryStateSelect' ;
        $rules['factoryStateSelect']= 'required_without_all:factoryState' ;
        $rules['factoryZipCode']= 'required';
        $rules['factoryCountry']= 'required';
        $rules['factoryPhone']= 'required';
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->messages()], 200);
        }
        $buyer = MetaBuyer::where('id', Auth::user()->buyer_meta_id)->first();
        $factory_state_id = null;
        $factory_state = null;
        if ($request->factoryLocation == "INT")
            $factory_state = $request->factoryState;
        else
            $factory_state_id = $request->factoryStateSelect;

        $buyer->billing_location = $request->factoryLocation;
        $buyer->billing_address = $request->factoryAddress;
        $buyer->billing_city = $request->factoryCity;
        $buyer->billing_state_id = $factory_state_id;
        $buyer->billing_state = $factory_state;
        $buyer->billing_zip = $request->factoryZipCode;
        $buyer->billing_country_id = $request->factoryCountry;
        $buyer->billing_phone = $request->factoryPhone;

        $buyer->save();
        return response()->json([], 200);
    }

    public function deleteShippingAddress(Request $request) {
        if(BuyerAddress::where('id', $request[0])->delete()){
            return response()->json(['message'=>'success'], 200);
        }else{
            return response()->json(['message'=>'faile'], 200);
        }
    }
}
