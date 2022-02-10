<?php

namespace App\Http\Controllers\Admin;

use App\Model\Promotion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class PromotionController extends Controller
{
    public function index() {
        $promotions = Promotion::orderBy('created_at', 'desc')->where('user_id', Auth::user()->id)->paginate(10);
        return view('admin.dashboard.administration.promotion.index', compact( 'promotions'))->with('page_title', 'Coupons');
    }

    public function addPost(Request $request) {
        $inputPromotion = array(
            'user_id' => Auth::user()->id,
            'status' => $request->status,
            'promotion_type' => $request->promotion_type,
            'title' => $request->title,
            'description' => $request->description,
            'is_permanent' => $request->is_permanent,
            'valid_from' => ($request->is_permanent == 0) ? $request->valid_from : '',
            'valid_to' => ($request->is_permanent == 0) ? $request->valid_to : '',
            'hasCouponCode' => ($request->hasCouponCode) ? 1 : 0,
            'coupon_code' => ($request->hasCouponCode == 1) ? $request->coupon_code : '',
            'multiple_use' => $request->multiple_use,
            'from_price_1' => $request->from_price_1,
            'to_price_1' => $request->to_price_1,
            'percentage_discount_1' => ($request->promotion_type == 'Percentage discount by order amount') ? $request->discount_1 : 0,
            'unit_price_discount_1' => ($request->promotion_type != 'Percentage discount by order amount') ? $request->discount_1 : 0,
            'free_shipping_1' => $request->free_shipping_1,
            'from_price_2' => $request->from_price_2,
            'to_price_2' => $request->to_price_2,
            'percentage_discount_2' => ($request->promotion_type == 'Percentage discount by order amount') ? $request->discount_2 : 0,
            'unit_price_discount_2' => ($request->promotion_type != 'Percentage discount by order amount') ? $request->discount_2 : 0,
            'free_shipping_2' => $request->free_shipping_2,
            'from_price_3' => $request->from_price_3,
            'to_price_3' => $request->to_price_3,
            'percentage_discount_3' => ($request->promotion_type == 'Percentage discount by order amount') ? $request->discount_3 : 0,
            'unit_price_discount_3' => ($request->promotion_type != 'Percentage discount by order amount') ? $request->discount_3 : 0,
            'free_shipping_3' => $request->free_shipping_3,
            'from_price_4' => $request->from_price_4,
            'to_price_4' => $request->to_price_4,
            'percentage_discount_4' => ($request->promotion_type == 'Percentage discount by order amount') ? $request->discount_4 : 0,
            'unit_price_discount_4' => ($request->promotion_type != 'Percentage discount by order amount') ? $request->discount_4 : 0,
            'free_shipping_4' => $request->free_shipping_4,
            'from_price_5' => $request->from_price_5,
            'to_price_5' => $request->to_price_5,
            'percentage_discount_5' => ($request->promotion_type == 'Percentage discount by order amount') ? $request->discount_5 : 0,
            'unit_price_discount_5' => ($request->promotion_type != 'Percentage discount by order amount') ? $request->discount_5 : 0,
            'free_shipping_5' => $request->free_shipping_5
        );

        //dd($inputPromotion);

        Promotion::create($inputPromotion);

        return redirect()->route('admin_promotions')->with('message', 'Coupon Has Been Added!');

    }

    public function editPost(Request $request) {

        $promotion = Promotion::where('id', $request->promotionId)->first();

        $promotion->status = $request->status;
        $promotion->promotion_type = $request->promotion_type;
        $promotion->title = $request->title;
        $promotion->description = $request->description;
        $promotion->is_permanent = $request->is_permanent;
        $promotion->valid_from = ($request->is_permanent == 0) ? $request->valid_from : '';
        $promotion->valid_to = ($request->is_permanent == 0) ? $request->valid_to : '';
        $promotion->hasCouponCode = ($request->hasCouponCode) ? 1 : 0;
        $promotion->coupon_code = ($request->hasCouponCode == 1) ? $request->coupon_code : '';
        $promotion->multiple_use = $request->multiple_use;
        $promotion->from_price_1 = $request->from_price_1;
        $promotion->to_price_1 = $request->to_price_1;
        $promotion->percentage_discount_1 = ($request->promotion_type == 'Percentage discount by order amount') ? $request->discount_1 : 0;
        $promotion->unit_price_discount_1 = ($request->promotion_type != 'Percentage discount by order amount') ? $request->discount_1 : 0;
        $promotion->free_shipping_1 = $request->free_shipping_1;
        $promotion->from_price_2 = $request->from_price_2;
        $promotion->to_price_2 = $request->to_price_2;
        $promotion->percentage_discount_2 = ($request->promotion_type == 'Percentage discount by order amount') ? $request->discount_2 : 0;
        $promotion->unit_price_discount_2 = ($request->promotion_type != 'Percentage discount by order amount') ? $request->discount_2 : 0;
        $promotion->free_shipping_2 = $request->free_shipping_2;
        $promotion->from_price_3 = $request->from_price_3;
        $promotion->to_price_3 = $request->to_price_3;
        $promotion->percentage_discount_3 = ($request->promotion_type == 'Percentage discount by order amount') ? $request->discount_3 : 0;
        $promotion->unit_price_discount_3 = ($request->promotion_type != 'Percentage discount by order amount') ? $request->discount_3 : 0;
        $promotion->free_shipping_3 = $request->free_shipping_3;
        $promotion->from_price_4 = $request->from_price_4;
        $promotion->to_price_4 = $request->to_price_4;
        $promotion->percentage_discount_4 = ($request->promotion_type == 'Percentage discount by order amount') ? $request->discount_4 : 0;
        $promotion->unit_price_discount_4 = ($request->promotion_type != 'Percentage discount by order amount') ? $request->discount_4 : 0;
        $promotion->free_shipping_4 = $request->free_shipping_4;
        $promotion->from_price_5 = $request->from_price_5;
        $promotion->to_price_5 = $request->to_price_5;
        $promotion->percentage_discount_5 = ($request->promotion_type == 'Percentage discount by order amount') ? $request->discount_5 : 0;
        $promotion->unit_price_discount_5 = ($request->promotion_type != 'Percentage discount by order amount') ? $request->discount_5 : 0;
        $promotion->free_shipping_5 = $request->free_shipping_5;

        $promotion->save();


        return redirect()->route('admin_promotions')->with('message', 'Coupon Has Been Updated!');

    }

    public function delete(Request $request) {
        Promotion::where('id', $request->id)->delete();
    }

}
