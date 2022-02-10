<?php

namespace App\Http\Controllers\Admin;

use Uuid;
use App\Enumeration\PageEnumeration;
use App\Model\Page;
use App\Model\Meta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function index($id) {

        $meta = Meta::where('page', $id)->first();
        if (!$meta) {
            $meta = Meta::create([
                'page' => $id
            ]);
        }

        $page = Page::where('page_id', $id)->first();
        if (!$page) {
            $page = Page::create([
                'page_id' => $id,
            ]);
        }

        $title = 'Page/Meta - ';

        if ($id == PageEnumeration::$HOME)
            $title .= 'Home';
        else if ($id == PageEnumeration::$ABOUT_US)
            $title .= 'About Us';
        else if ($id == PageEnumeration::$RETURN_INFO)
            $title .= 'Return Policy';
        else if ($id == PageEnumeration::$TERMS_AND_CONDIOTIONS)
            $title .= 'Terms and Conditions';
        else if ($id == PageEnumeration::$PRIVACY_POLICY)
            $title .= 'Privacy Policy';
        else if ($id == PageEnumeration::$CONTACT_US)
            $title .= 'Contact Us';
        else if ($id == PageEnumeration::$FAQ)
            $title .= 'FAQ';
        else if ($id == PageEnumeration::$SIZE_GUIDE)
            $title .= 'Size Guide';
        else if ($id == PageEnumeration::$HOME_PAGE_CUSTOM_SECTION)
            $title .= 'Home Page Custome Section';

        return view('admin.dashboard.page.index', compact('page', 'meta'))->with('page_title', $title);
    }

    public function save(Request $request, $id) {

        if($request->file('image')){

            $request->validate([
                'image' => 'required|mimes:jpg,jpeg,png,webp|dimensions:min_width=200,min_height=200'
            ]);
    
            $file = $request->file('image');
            $filename = Uuid::generate()->string;
            $ext = $file->getClientOriginalExtension();
            $destinationPath = 'images/banner';
            $file->move(public_path($destinationPath), $filename.".".$ext);
            $imagePath = $destinationPath."/".$filename.".".$ext;
        }else{
            $meta = Meta::where('page', $id)->first();
            if(!empty($meta->image)){
                $imagePath = $meta->image;
            }else{
                $imagePath = null; 
            }
        }

        Page::where('page_id', $id)->update([
            'content' => $request->page_editor,
            'url' => $request->url,
        ]);

        Meta::where('page', $id)->update([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return redirect()->back()->with('message', 'Updated!');
    }
}
