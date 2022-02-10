<?php

namespace App\Http\Controllers;

use App\Enumeration\PageEnumeration;
use App\Model\Page;
use Illuminate\Http\Request;
use App\Model\Order;
use App\Enumeration\OrderStatus;
use Illuminate\Support\Facades\Auth;

class StaticPageController extends Controller
{
    public function aboutUs() {
        $content = '';

        $page = Page::where('page_id', PageEnumeration::$ABOUT_US)->first();
        if ($page)
            $content = $page->content;


        return view('pages.static', compact('content'))->with('title', 'WELCOME TO BELITA COLLECTION');
    }

    public function contactUs() {
        $content = '';

        $page = Page::where('page_id', PageEnumeration::$CONTACT_US)->first();

        if ($page)
            $content = $page->content; 
        return view('pages.static', compact('content'))->with('title', 'Contact Us'); 
    }

    public function returnPolicy() {
        $content = '';

        $page = Page::where('page_id', PageEnumeration::$RETURN_POLICY)->first();
        if ($page)
            $content = $page->content;


        return view('pages.static', compact('content'))->with('title', 'Privacy Policy');
    }

    public function contactUsPost(Request $request) {
        return 'we are comming soon';
    }

    public function termsCondiditions() {
        $content = '';

        $page = Page::where('page_id', PageEnumeration::$TERMS_AND_CONDIOTIONS)->first();

        if ($page)
            $content = $page->content;


        return view('pages.static', compact('content'))->with('title', 'Terms & Conditions');
    }
    
    public function sizeGuide() 
    {
        $content = '';

        $page = Page::where('page_id', PageEnumeration::$SIZE_GUIDE)->first();
        if ($page)
            $content = $page->content;

        return view('pages.static', compact('content'))->with('title', 'Size Guide');
    }

    public function checkOrders() 
    {
        $orders = Order::where('status', '!=', OrderStatus::$INIT)->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();

        return view('buyer.profile.orders', compact('orders'))->with('title', 'Check Orders');
    }

    public function myaccount(){
        return view('pages.myaccount');
    }
    public function mysave(){
        return view('pages.mysave');
    }
    public function complete(){
        return view('buyer.checkout.complete');
    }
}
