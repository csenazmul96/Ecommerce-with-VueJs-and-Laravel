<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\PointSystem;
use DB;

class PointSystemController extends Controller
{
    public function index() {
    	$pointSetting = DB::table('point_system_settings')->first();
    	$pointDiscount = DB::table('point_dollar_discount')->first();
        $points = PointSystem::get();
        return view('admin.dashboard.administration.point_system.index',compact('pointSetting','pointDiscount','points'))->with('page_title', 'Point System');
    }

    //settings
    public function saveSetting(Request $request) {
    	if (is_numeric($request->cost_dollars) || is_numeric($request->point_rewards)) {
    		$data = [
    			'cost_dollars' => $request->cost_dollars,
    			'point_rewards' => $request->point_rewards,
    			'status' => $request->status
    		];
            DB::table('point_system_settings')->truncate();
            DB::table('point_system_settings')->insert($data);
            return response()->json(['success' => true, 'message' => 'Settings Saved!']);
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid data.']);
        }
    }
    
    //disocunts
    public function saveDiscount(Request $request) {
    	if (is_numeric($request->points_use) || is_numeric($request->dollar_disounts)) {
    		$data = [
    			'points_use' => $request->points_use,
    			'dollar_disounts' => $request->dollar_disounts
    		];
            DB::table('point_dollar_discount')->truncate();
            DB::table('point_dollar_discount')->insert($data);
            return response()->json(['success' => true, 'message' => 'Data Saved!']);
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid data.']);
        }
    }
    //points

    public function addPoint(Request $request) {

        $inputPoint = array(
            'status' => $request->status,
            'point_type' => $request->point_type,
            'from_price_1' => $request->from_price_1,
            'percentage_discount_1' => ($request->point_type == 'Percentage discount by order amount') ? $request->discount_1 : 0,
            'unit_price_discount_1' => ($request->point_type != 'Percentage discount by order amount') ? $request->discount_1 : 0,
            'free_shipping_1' => $request->free_shipping_1
        );
        PointSystem::create($inputPoint);

        return redirect()->route('admin_point_system')->with('message', 'Point Has Been Added!');

    }

    public function editPoint(Request $request) {

        $point = PointSystem::where('id', $request->pointId)->first();

        $point->status = $request->status;
        $point->point_type = $request->point_type;
        $point->from_price_1 = $request->from_price_1;
        $point->percentage_discount_1 = ($request->point_type == 'Percentage discount by order amount') ? $request->discount_1 : 0;
        $point->unit_price_discount_1 = ($request->point_type != 'Percentage discount by order amount') ? $request->discount_1 : 0;
        $point->free_shipping_1 = $request->free_shipping_1;

        $point->save();

        return redirect()->route('admin_point_system')->with('message', 'Point Has Been Updated!');

    }

    public function delete(Request $request) {
        PointSystem::where('id', $request->id)->delete();
    }
}