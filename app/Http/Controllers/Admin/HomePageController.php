<?php

namespace App\Http\Controllers\Admin;

use Uuid;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Meta;
use App\Model\HomePage;

class HomePageController extends Controller
{
    public function index() {
        $meta = Meta::where('page', 1)->first();
        if (!$meta) {
            $meta = Meta::create([
                'page' => 1
            ]);
        }
        $sectionOne = HomePage::where('section_id', 1)->first();
        if (!$sectionOne) {
            $sectionOne = HomePage::create([
                'section_id' => 1,
            ]);
        }
        $sectionThree = HomePage::where('section_id', 3)->first();
        if (!$sectionThree) {
            $sectionThree = HomePage::create([
                'section_id' => 3,
            ]);
        }
        $sectionFour = HomePage::where('section_id', 4)->first();
        if (!$sectionFour) {
            $sectionFour = HomePage::create([
                'section_id' => 4,
            ]);
        }
        $sectionFive = HomePage::where('section_id', 5)->first();
        if (!$sectionFive) {
            $sectionFive = HomePage::create([
                'section_id' => 5,
            ]);
        }
        return view('admin.dashboard.home_page.index', compact('meta','sectionOne','sectionThree','sectionFour','sectionFive'))->with('page_title', 'Page/Meta - Home');
    }

    public function save(Request $request, $id) {

        HomePage::where('section_id', $id)->update([
            'content' => $request->page_editor
        ]);

        return redirect()->back()->with('message', 'Updated!');
    }

    public function metaSave(Request $request) {

        $request->validate([
            'image' => 'nullable|mimes:jpg,jpeg,png|dimensions:min_width=200,min_height=200'
        ]);
        $meta = Meta::where('page', 1)->first();
        $imagePath = null;
        if($meta)
            $imagePath = $meta->image;
        else
            $meta = new Meta();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = Uuid::generate()->string;
            $ext = $file->getClientOriginalExtension();
            $destinationPath = 'images/banner';
            $file->move(public_path($destinationPath), $filename . "." . $ext);
            $imagePath = $destinationPath . "/" . $filename . "." . $ext;
        }
        $meta->title = $request->title;
        $meta->description = $request->description;
        $meta->image = $imagePath;
        $meta->save();

        return redirect()->back()->with('message', 'Updated!');
    }
}
