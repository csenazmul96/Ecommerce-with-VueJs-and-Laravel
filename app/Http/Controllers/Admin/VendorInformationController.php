<?php

namespace App\Http\Controllers\Admin;

use App\Model\Country;
use App\Model\MetaVendor;
use App\Model\State;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class VendorInformationController extends Controller
{
    public function index() {
        $user = Auth::user();
        $user->load('vendor');
        $vendor = MetaVendor::where('id', 1)->first();

        $countries = Country::orderBy('name')->get();
        $usStates = State::where('country_id', 1)->orderBy('name')->get()->toArray();
        $caStates =State::where('country_id', 2)->orderBy('name')->get()->toArray();

        return view('admin.dashboard.administration.vendor_information.index', compact('user', 'countries',
            'usStates', 'caStates', 'vendor'))
            ->with('page_title', 'Admin Information');
    }

    public function companyInformationPost(Request $request) {
        $request->validate([
            'company_description' => 'nullable|max:1000',
            'showroom_address' => 'required|max:255',
            'showroom_city' => 'required|max:255',
            'showroom_zip_code' => 'required|max:255',
            'showroom_country' => 'required',
            'showroom_tel' => 'required|max:255',
            'showroom_alt' => 'nullable|max:255',
            'warehouse_address' => 'required|max:255',
            'warehouse_city' => 'required|max:255',
            'warehouse_zip_code' => 'required|max:255',
            'warehouse_country' => 'required',
            'warehouse_tel' => 'required|max:255',
            'warehouse_alt' => 'nullable|max:255',
        ]);

        $user = Auth::user();
        $user->load('vendor');

        $user->vendor->company_info = $request->company_description;
        $user->vendor->billing_address = $request->showroom_address;
        $user->vendor->billing_city = $request->showroom_city;
        $user->vendor->billing_zip = $request->showroom_zip_code;
        $user->vendor->billing_country_id = $request->showroom_country;
        $user->vendor->billing_phone = $request->showroom_tel;
        $user->vendor->billing_alternate_phone = $request->showroom_alt;
        $user->vendor->billing_fax = $request->showroom_fax;
        $user->vendor->factory_address = $request->warehouse_address;
        $user->vendor->factory_city = $request->warehouse_city;
        $user->vendor->factory_zip = $request->warehouse_zip_code;
        $user->vendor->factory_country_id = $request->warehouse_country;
        $user->vendor->factory_phone = $request->warehouse_tel;
        $user->vendor->factory_alternate_phone = $request->warehouse_alt;
        $user->vendor->factory_fax = $request->warehouse_fax;

        $showroomState = null;
        $warehouseState = null;
        if ($request->warehouse_state && $request->warehouse_state != '')
            $warehouseState = $request->warehouse_state;

        if ($request->showroom_state && $request->showroom_state != '')
            $showroomState = $request->showroom_state;

        $user->vendor->billing_state_id = $showroomState;
        $user->vendor->factory_state_id = $warehouseState;


        $user->vendor->save();
        $user->save();

        // return redirect()->route('admin_admin_information')->with('message', 'Company Info Updated!');
    }

    public function sizeChartPost(Request $request) {
        $user = Auth::user();
        $user->load('vendor');
        $user->vendor->size_chart = $request->description;
        $user->vendor->save();
    }

    public function orderNoticePost(Request $request) {
        $user = Auth::user();
        $user->load('vendor');
        $user->vendor->order_notice = $request->description;
        $user->vendor->save();
    }

    public function adminReturnPolicypost(Request $request) {
        $user = Auth::user();
        $user->load('vendor');
        $user->vendor->order_notice = $request->description;
        $user->vendor->save();
        return back();
    }
    public function shippingPolicySave(Request $request) {
        $user = Auth::user();
        $user->load('vendor');
        $user->vendor->shipping = $request->description;
        $user->vendor->save();
        return back();
    }

    public function stylePickPost(Request $request) {
        $vendor = MetaVendor::where('id', 1)->first();

        $vendor->sp_vendor = $request->vendor_name;
        $vendor->sp_password = $request->vendor_password;
        $vendor->sp_category = $request->vendor_category;
        $vendor->sp_default_category = $request->default_category;
        $vendor->save();
    }

    public function saveSetting(Request $request) {
        if (is_numeric($request->min_order)) {
            MetaVendor::where('id', 1)->update([
                'min_order' => $request->min_order,
            ]);

            return response()->json(['success' => true, 'message' => 'Settings Saved!']);
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid data.']);
        }
    }
}
