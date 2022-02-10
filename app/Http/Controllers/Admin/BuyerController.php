<?php

namespace App\Http\Controllers\Admin;

use Uuid;
use DB;
use App\Model\User;
use App\Model\State;
use App\Model\Country;
use App\Model\AgeGroup;
use App\Model\SkinType;
use App\Model\MetaBuyer;
use App\Enumeration\Role;
use App\Model\StoreCredit;
use Illuminate\Http\Request;
use App\Events\UserRegistered;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Model\StoreCreditTransection;
use Illuminate\Support\Facades\Mail; 
use Spatie\Newsletter\NewsletterFacade as Newsletter;

class BuyerController extends Controller
{
    protected $allCustomerLists;

    public function __construct(MetaBuyer $allCustomerLists)
    {
        $this->allCustomerLists = $allCustomerLists;
    }
    public function allBuyer(Request $request) {
        $data['page_title'] = 'All Customer';
        $all=$this->allCustomerLists;

        if($request->has('customer_email') && $request->customer_email != null){
            $all = $all->leftJoin('users', 'meta_buyers.id', '=', 'users.buyer_meta_id');
            $all =  $all->select('meta_buyers.*', 'users.buyer_meta_id', 'users.email')->where('users.email', 'like','%' .$request->customer_email. '%');
        }

        if($request->has('customer_name')&& $request->customer_name!=null){
            $get_user_name_id = User::Select('id')->whereRaw('CONCAT(first_name, " ", last_name) LIKE ? ', '%' . $request->customer_name . '%')->get();
            $all= $all->whereIn('user_id',$get_user_name_id);
        }
        if($request->has('filter')&& $request->filter!=null){
            if($request->filter == 'name'){  
                $all = $all->leftJoin('users', 'meta_buyers.id', '=', 'users.buyer_meta_id');
                $all =  $all->select('meta_buyers.*', 'users.buyer_meta_id', 'users.first_name')->orderBy('users.first_name', $request->value);
            }else if($request->filter == 'email'){  
                $all = $all->leftJoin('users', 'meta_buyers.id', '=', 'users.buyer_meta_id');
                $all =  $all->select('meta_buyers.*', 'users.buyer_meta_id', 'users.email')->orderBy('users.email', $request->value); 
            }else if($request->filter == 'create_at'){  
                $all= $all->orderBy('created_at', $request->value);
            }else if($request->filter == 'last_login'){  
                $all = $all->leftJoin('users', 'meta_buyers.id', '=', 'users.buyer_meta_id');
                $all =  $all->select('meta_buyers.*', 'users.buyer_meta_id', 'users.last_login')->orderBy('users.last_login', $request->value);
            } 
        }else{
           $all->orderBy('created_at', 'desc');
        }

        if (isset($request->sort_by)) {
            if ($request->sort_by=='all') {
                $all=$all->orderBy('created_at', 'desc');
            }elseif($request->sort_by=='block'){
                $statusBlock = ($request->sort_by == 'block') ? 1:0;
                $all=$all->where('block','=', $statusBlock);
            }elseif($request->sort_by=='point_asc'){
                $all= $all->orderBy(DB::raw("`points` - `points_spent`"), 'asc');
            }elseif($request->sort_by=='point_desc'){
                $all= $all->orderBy(DB::raw("`points` - `points_spent`"), 'desc');
            }else{
                $all=$all->orderBy('created_at', 'desc');
            }
        }else{
            $all=$all->orderBy('created_at', 'desc');
        }

        $data['buyers'] = $all->with('user', 'userLastLogin')->where('meta_buyers.deleted_at', '=', null)->paginate(20);

        return view('admin.dashboard.customer.all', $data);
    }

    public function changeStatus(Request $request) {
        $buyers = MetaBuyer::where('id', $request->id)->first();
        $buyers->active = $request->status;
        $buyers->save();
    }

    public function changeVerified(Request $request) {
        $buyers = MetaBuyer::where('id', $request->id)->first();
        $buyers->verified = $request->status;
        $buyers->save();
    }

    public function changeMailingList(Request $request) {
        $buyers = MetaBuyer::where('id', $request->id)->first();
        $buyers->mailing_list = $request->mailing_list;
        $buyers->save();

        // Get user data by id
        $user = User::find($request->user_id);
        $user->phone = $request->billing_phone;
        
        // Trigger event for mailchimp
        event(new UserRegistered($user));
    }

    public function changeBlock(Request $request) {
        $buyers = User::where('id', $request->id)->first();
        $buyers->active = $request->status;
        $buyers->save();
    }


    public function edit(MetaBuyer $buyer) {
        $buyer->load('user');
        $ageGroups = AgeGroup::orderBy('lower_limit')->get();
        $skinTypes = SkinType::orderBy('type')->get();
        $countries = Country::orderBy('name')->get();
        $usStates = State::where('country_id', 1)->orderBy('name')->get()->toArray();
        $caStates =State::where('country_id', 2)->orderBy('name')->get()->toArray();

        return view('admin.dashboard.customer.edit', compact('ageGroups', 'skinTypes', 'countries', 'usStates', 'caStates', 'buyer'))->with('page_title', 'Edit Customer');
    }

    public function editPost(MetaBuyer $buyer, Request $request) {
        $messages = [
            'required' => 'This field is required.',
        ];

        $rules = [
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$buyer->user->id,
            'address' => 'required|string|max:255',
            'unit' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'zipCode' => 'required|string|max:255',
            'country' => 'required',
            'phone' => 'required|max:255',
            'fax' => 'nullable|max:255',
            'billingAddress' => 'required|string|max:255',
            'billingUnit' => 'nullable|string|max:255',
            'billingCity' => 'required|string|max:255',
            'billingZipCode' => 'required|string|max:255',
            'billingCountry' => 'required',
            'billingPhone' => 'required|max:255',
            'billingFax' => 'nullable|max:255',
        ];

        if ($request->location == "INT")
            $rules['state'] = 'required|string|max:255';
        else
            $rules['stateSelect'] = 'required';

        if ($request->billingLocation == "INT")
            $rules['billingState'] = 'required|string|max:255';
        else
            $rules['billingStateSelect'] = 'required';
 

        if ($request->password != '')
            $rules['password'] = 'required|string|min:6';

        $request->validate($rules, $messages);

        $state_id = null;
        $state = null;
        $billing_state_id = null;
        $billing_state = null;

        if ($request->location == "INT")
            $state = $request->state;
        else
            $state_id = $request->stateSelect;

        if ($request->billingLocation == "INT")
            $billing_state = $request->billingState;
        else
            $billing_state_id = $request->billingStateSelect;

        $buyer->shipping_location = $request->location;
        $buyer->shipping_address = $request->address;
        $buyer->shipping_city = $request->city;
        $buyer->shipping_state_id = $state_id;
        $buyer->shipping_state = $state;
        $buyer->shipping_zip = $request->zipCode;
        $buyer->shipping_country_id = $request->country;
        $buyer->shipping_phone = $request->phone;
        $buyer->shipping_fax = $request->fax;
        $buyer->shipping_unit = $request->unit;
        $buyer->billing_location = $request->billingLocation;
        $buyer->billing_address = $request->billingAddress;
        $buyer->billing_unit = $request->billingUnit;
        $buyer->billing_state_id = $billing_state_id;
        $buyer->billing_state = $billing_state;
        $buyer->billing_unit = $request->billingUnit;
        $buyer->billing_zip = $request->billingZipCode;
        $buyer->billing_country_id = $request->billingCountry;
        $buyer->billing_phone = $request->billingPhone;
        $buyer->billing_fax = $request->billingFax;
        $buyer->receive_offers = $request->receiveSpecialOffers;
        $buyer->user->first_name = $request->firstName;
        $buyer->user->last_name = $request->lastName;
        $buyer->user->email = $request->email;

        if ($request->password != '')
            $buyer->user->password = Hash::make($request->password);

        $buyer->save();
        $buyer->user->save();

        return redirect()->route('admin_all_buyer')->with('message', 'Updated!');
    }

    public function delete(Request $request) {
        $meta = MetaBuyer::where('id', $request->id)->first();
        $user = User::where('id', $meta->user->id)->first();
        $user->email = $user->email.'-deleted';
        $user->save();

        $user->delete();
        $meta->delete();
    }

    public function allBuyerExport() {
        $allCustomer = User::with('buyer')->get();
        $customersArray = [];
        $stateID=null;
        foreach ($allCustomer as $customer) {
            $sid=\App\Model\State::where('id',(isset($customer->buyer->billing_state_id)) ? $customer->buyer->billing_state_id:null)->first();
            if($sid){
                $stateID =$sid->code;
            }
            $customersArray[] = ['Business Name' => (isset($customer->buyer->company_name)) ? $customer->buyer->company_name : null,
                'Name' => $customer->first_name . ' ' . $customer->last_name,
                'Email' => $customer->email,
                'Address' => (isset($customer->buyer->billing_address)) ? $customer->buyer->billing_address : null,
                'Unit #' => (isset($customer->buyer->billing_unit)) ? $customer->buyer->billing_unit : null,
                'Phone' => (isset($customer->buyer->billing_phone)) ? $customer->buyer->billing_phone : null,
                'City' => (isset($customer->buyer->billing_city)) ? $customer->buyer->billing_city : null,
                'State' => (isset($stateID)) ? $stateID : null,
                'Zipcode' => (isset($customer->buyer->billing_zip)) ? $customer->buyer->billing_zip : null,
                'Fax' => (isset($customer->buyer->billing_fax)) ? $customer->buyer->billing_fax : null,
                'Website' => (isset($customer->buyer->website)) ? $customer->buyer->website : null,
                'Approved?' => ($customer->active == 1) ? 'Y' : 'N',
                'Approved At' => $customer->updated_at,
                'Created At' => $customer->created_at,
                'Orders' => $customer->order_count,
                'Last Login' => $customer->last_login];
        }

        // Generate and return the spreadsheet
        $excel = App::make('excel');
        $excel->create('customers', function($excel) use ($customersArray) {
            // Set the spreadsheet title, creator, and description
            //$excel->setTitle('Customer');
           // $excel->setCreator('CustomersLists')->setCompany('CQBYCQ');
            //$excel->setDescription('customer lists file');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('CustomerLists', function($sheet) use ($customersArray) {
           $sheet->fromArray($customersArray);
            });

        })->download('xlsx');
        return redirect()->back();
    }

    public function customerCreate(){
        $ageGroups = AgeGroup::orderBy('lower_limit')->get();
        $skinTypes = SkinType::orderBy('type')->get();
        $countries = Country::orderBy('name')->get();
        $usStates = State::where('country_id', 1)->orderBy('name')->get()->toArray();
        $caStates = State::where('country_id', 2)->orderBy('name')->get()->toArray();

        return view('admin.dashboard.customer.create', compact('ageGroups', 'skinTypes', 'countries', 'usStates', 'caStates'))->with('page_title', 'Create Customer');
    }

    public function customerPost(Request $request){
        $messages = [
            'required' => 'This field is required.',
            'required_without_all' => 'The :attribute field is required when none of :values are present.',
        ];

        $rules = [
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            // 'age_group' => 'required',
            // 'skin_type' => 'required',
            'address' => 'required|string|max:255',
            'unit' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'zipCode' => 'required|string|max:255',
            'country' => 'required',
            'phone' => 'required|max:255',
            'fax' => 'nullable|max:255',
            'billingAddress' => 'required|string|max:255',
            'billingUnit' => 'nullable|string|max:255',
            'billingCity' => 'required|string|max:255',
            'billingZipCode' => 'required|string|max:255',
            'billingCountry' => 'required',
            'billingPhone' => 'required|max:255',
            'billingFax' => 'nullable|max:255',
        ];
          

        if ($request->location == "INT")
            $rules['state'] = 'required|string|max:255';
        else
            $rules['stateSelect'] = 'required';

        $request->validate($rules, $messages);

        $state_id = null;
        $state = null;
        $billing_state_id = null;
        $billing_state = null;
        
        if ($request->location == "INT")
            $state = $request->state;
        else
            $state_id = $request->stateSelect;

        if ($request->billingLocation == "INT")
            $billing_state = $request->billingState;
        else
            $billing_state_id = $request->billingStateSelect;

        $meta = MetaBuyer::create([
            'verified' => 1,
            'active' => 1,
            'user_id' => 0,
            // 'age_group_id' => $request->age_group,
            // 'skin_type_id' => $request->skin_type,
            'shipping_location' => $request->location,
            'shipping_address' => $request->address,
            'shipping_city' => $request->city,
            'shipping_state_id' => $state_id,
            'shipping_state' => $state,
            'shipping_zip' => $request->zipCode,
            'shipping_country_id' => $request->country,
            'shipping_phone' => $request->phone,
            'shipping_fax' => $request->fax,
            'shipping_unit' => $request->unit,
            'billing_location' => $request->billingLocation,
            'billing_address' => $request->billingAddress,
            'billing_unit' => $request->billingUnit,
            'billing_city' => $request->billingCity,
            'billing_state_id' => $billing_state_id,
            'billing_state' => $billing_state,
            'billing_unit' => $request->billingUnit,
            'billing_zip' => $request->billingZipCode,
            'billing_country_id' => $request->billingCountry,
            'billing_phone' => $request->billingPhone,
            'billing_fax' => $request->billingFax,
            'receive_offers' => $request->receiveSpecialOffers,
        ]);

        $user = User::create([
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => Role::$BUYER,
            'buyer_meta_id' => $meta->id,
        ]);

        $meta->user_id = $user->id;
        $meta->save();
         
        // Trigger event for mailchimp 
        // if(! Newsletter::isSubscribed($user->email)){
        //     Newsletter::subscribePending($user->email);  
        // }
         
        // event(new UserRegistered($user));

        // Send Mail to User
        // Mail::send('emails.buyer.registration_complete', [], function ($message) use ($request) {
        //     $message->subject('Registration Complete');
        //     $message->to($request->email, $request->firstName.' '.$request->lastName);
        // });

        return redirect()->route('customer_register_complete');
    }
    
    public function customerComplete() {
        return view('admin.dashboard.customer.complete')->with('page_title', 'Customer Registration Complete');;
    }
}
