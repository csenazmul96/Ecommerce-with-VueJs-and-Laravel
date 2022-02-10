<?php

namespace App\Http\Controllers\Admin;

use App\Model\AdminShipMethod;
use App\Model\Courier;
use App\Model\ShippingMethod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShipMethodController extends Controller
{
    public function index(Request $request) {
        $parameters = [];
        $appends = [];

        if ($request->s && $request->s != null) {
            $parameters[] = ['name', 'like', '%' . $request->s . '%'];
            $appends['s'] = $request->s;
        }
        $shipMethods = AdminShipMethod::with('courier')->where($parameters)->orderBy('name')->paginate(10);
        $couriers = Courier::orderBy('name')->get();

        return view('admin.dashboard.administration.ship_method.index', compact('shipMethods', 'couriers', 'appends'))->with('page_title', 'Ship Method');
    }

    public function addPost(Request $request) {

        $request->validate([
            'ship_method' => 'required|unique:admin_ship_methods,name',
            'courier' => 'required',
            'type' => 'required',
            'fee' => 'nullable|numeric'
        ]);

        AdminShipMethod::create([
            'name' => $request->ship_method,
            'type' => $request->type,
            'courier_id' => $request->courier,
            'fee' => $request->fee,
        ]);

        return redirect()->route('admin_ship_method')->with('message', 'Ship Method Added!');
    }

    public function update(Request $request) {
        $request->validate([
            'ship_method' => 'required|unique:admin_ship_methods,name,'.$request->shipMethodId,
            'courier' => 'required',
            'type' => 'required',
            'fee' => 'nullable|numeric'
        ]);


        AdminShipMethod::where('id', $request->shipMethodId)->update([
            'name' => $request->ship_method,
            'courier_id' =>  $request->courier,
            'type' =>  $request->type,
            'fee' => $request->fee,
        ]);

        return redirect()->route('admin_ship_method')->with('message', 'Ship Method Updated!');
    }

    public function delete(Request $request) {
        ShippingMethod::where('ship_method_id', $request->id)->delete();
        AdminShipMethod::where('id', $request->id)->delete();
    }
}
