<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::orderBy('sort', 'asc')->get();
        return view('admin.dashboard.faq.index', compact('faqs'))->with('page_title', 'Faqs');
    }

    public function addNewFaqPost(Request $request)
    {
        $request->validate([
            'question'=> 'required',
            'answer'=> 'required',
        ]);

        $sort = Faq::max('sort');
        $msg = null;
        if($request->id){
            $faq = Faq::find($request->id);
            $msg = 'Faq edit successfull';
        }else {
            $faq = new Faq();
            $msg = 'Faq add successfull';
        }
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->sort = $sort+1;
        $faq->save();

        return redirect()->route('faqs')->with('message', $msg);
    }

    public function faqDelete(Request $request)
    {
         $faq = Faq::find($request->id);
         if($faq)
             $faq->delete();
        return redirect()->route('faqs')->with('message', 'Faq delete successfull');
    }
}
