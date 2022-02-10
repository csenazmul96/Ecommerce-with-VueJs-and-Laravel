<?php

namespace App\Http\Controllers\Admin;

use App\Enumeration\CouponType;
use App\Model\SectionHeading;
use App\Model\SocialLinks;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use DB;

class SocialController extends Controller
{
    public function index() {
        $socialLinks = SocialLinks::orderBy('created_at', 'desc')->get();
        return view('admin.dashboard.administration.social_links.index', compact('socialLinks'))->with('page_title', 'Social Links');
    }

    public function addUpdatePost(Request $request) {

        $checkSocialLinks = SocialLinks::count();
        if ( $checkSocialLinks > 0 ) {
            $updateData = [
                'facebook' => isset($request->facebook) ? $request->facebook : '',
                'twitter' => isset($request->twitter) ? $request->twitter : '',
                'pinterest' => isset($request->pinterest) ? $request->pinterest : '',
                'instagram' => isset($request->instagram) ? $request->instagram : '',
            ];
            SocialLinks::first()->update($updateData);
        }
        else {
            SocialLinks::create([
                'facebook' =>isset($request->facebook) ? $request->facebook : '',
                'twitter' => isset($request->twitter) ? $request->twitter : '',
                'pinterest' => isset($request->pinterest) ? $request->pinterest : '',
                'instagram' => isset($request->instagram) ? $request->instagram : '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }

        return redirect()->back()->with('message', 'Information updated!');
    }

    public function social_feed_access(){
        $social_feeds = DB::table('social_feeds')->get();
        $data_array = array();
        if(count($social_feeds) == 0)
        {

            $data_array['instagram']['access_token'] = "";
        }else{
            $data_array['instagram']['access_token'] = DB::table('social_feeds')->select('access_token')->where('type','instagram')->get()[0]->access_token;
        }
        $section_heading = SectionHeading::where('section_name', 'instagram_section')->first();

        return view('admin.dashboard.administration.social_feeds.index', compact('data_array','section_heading'))->with('page_title', 'Social Feeds');
    }

    public function socialFeedaddUpdatePost(request $request){
        $social_feeds = DB::table('social_feeds')->get();
        if(sizeof($social_feeds) == 0){
            $data = array();
            $data['type'] = 'instagram';
            $data['access_token'] = $request->instagram_access_token;
            DB::table('social_feeds')->insert($data);

            return redirect()->route('admin_social_feed')->with('message', 'Successfully added!');

        }else{
            $request->validate([
                'heading'=>'required'
            ]);
            $data = array();
            $data['type'] = 'instagram';
            $data['access_token'] = $request->instagram_access_token;
            DB::table('social_feeds')->where('type','instagram')->update($data);

            SectionHeading::where('section_name', 'instagram_section')->update(['heading'=>$request->heading]);

            return redirect()->route('admin_social_feed')->with('message', 'Successfully updated!');
        }
    }
}
