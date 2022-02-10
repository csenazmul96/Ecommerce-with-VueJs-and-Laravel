<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\PrivacyNotice;

class PrivacyNoticeController extends Controller
{
    public function index()
    {
        $notices = PrivacyNotice::latest()->get();

        return view('admin.dashboard.privacy_notice.index', compact('notices'))->with('page_title', 'Privacy Notice');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'description'=>'required',
        ]);

        $notice = new PrivacyNotice();
        $notice->name = $request->name;
        $notice->description = $request->description;
        $notice->status = $request->status;
        $notice->save();

        return redirect()->route('privacy_notice');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'description'=>'required',
        ]);

        $notice = PrivacyNotice::find($request->id);
        $notice->name = $request->name;
        $notice->description = $request->description;
        $notice->status = $request->status;
        $notice->save();

        return redirect()->route('privacy_notice');
    }

    public function statusChange(Request $request)
    {
        $notice = PrivacyNotice::where('id', $request->id)->first();
        $notice->status = $request->status;
        $notice->save();
    }

    public function destroy(Request $request)
    {
        $notice = PrivacyNotice::find($request->id);
        if($notice)
            $notice->delete();
    }
}
