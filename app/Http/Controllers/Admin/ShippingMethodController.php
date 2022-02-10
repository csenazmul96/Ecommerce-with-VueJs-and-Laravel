<?php

namespace App\Http\Controllers\Admin;

use App\Model\Courier;
use App\Model\ShippingMethod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class ShippingMethodController extends Controller
{
    public function index() {
        $couriers = Courier::with('shipMethods')->orderBy('name')->get();
        $shippingMethods = ShippingMethod::with('courier', 'shipMethod')
            ->orderBy('list_order')->get();

        return view('admin.dashboard.administration.shipping_method.index', compact('shippingMethods', 'couriers'))
            ->with('page_title', 'Shipping Methods');
    }

    public function addPost(Request $request) {
        $request->validate([
            'courier' => 'required',
            'type' => 'required',
            'ship_method' => 'required',
            'list_order' => 'required|integer'
        ]);

        if ($request->default) {
            ShippingMethod::where([])->update([ 'default' => 0 ]);
        }

        ShippingMethod::create([
            'status' => $request->status,
            'default' => ($request->default) ? 1 : 0,
            'courier_id' => $request->courier,
            'type' => $request->type,
            'ship_method_id' => $request->ship_method,
            'list_order' => $request->list_order
        ]);

        return redirect()->back()->with('message', 'Ship Method Added!');
    }

    public function editPost(Request $request) {
        $request->validate([
            'courier' => 'required',
            'type' => 'required',
            'ship_method' => 'required',
            'list_order' => 'required|integer'
        ]);

        if ($request->default) {
            ShippingMethod::where([])->update([ 'default' => 0 ]);
        }

        ShippingMethod::where('id', $request->shippingMethodId)->update([
            'status' => $request->status,
            'default' => ($request->default) ? 1 : 0,
            'courier_id' => $request->courier,
            'type' => $request->type,
            'ship_method_id' => $request->ship_method,
            'list_order' => $request->list_order,
        ]);

        return redirect()->back()->with('message', 'Ship Method Updated!');
    }

    public function delete(Request $request) {
        ShippingMethod::where('id', $request->id)->delete();
    }

    public function changeStatus(Request $request) {
        ShippingMethod::where('id', $request->id)->update(['status' => $request->status]);
    }

    public function changeDefault(Request $request) {
        ShippingMethod::where([])->update([ 'default' => 0 ]);
        ShippingMethod::where('id', $request->id)->update([ 'default' => 1 ]);
    }
}
