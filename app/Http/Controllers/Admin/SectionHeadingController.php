<?php

namespace App\Http\Controllers\Admin;

use App\Model\SectionHeading;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SectionHeadingController extends Controller
{
    public function index()
    {
        $sections = SectionHeading::all();
        return view('admin.dashboard.section_heading.index', compact('sections'))->with('page_title', 'Section Heading');
    }

    public function sectionPost(Request $request)
    {
        $request->validate([
            'heading' => 'required'
        ]);

        $section = SectionHeading::find($request->id);
        $section->heading = $request->heading;
        $section->description = $request->description;
        $section->save();

        return redirect()->back();
    }
}
