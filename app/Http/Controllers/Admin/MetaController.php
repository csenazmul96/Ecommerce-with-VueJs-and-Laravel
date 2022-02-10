<?php

namespace App\Http\Controllers\Admin;

use App\Enumeration\Page;
use App\Enumeration\PageEnumeration;
use App\Model\Meta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MetaController extends Controller
{
    public function page($page) {
        $meta = Meta::where('page', $page)->first();

        if (!$meta) {
            $meta = Meta::create([
                'page' => $page
            ]);
        }

        return view('admin.dashboard.meta.index', compact('meta'))->with('page_title', 'Meta');
    }

    public function category($category) {
        $meta = Meta::where('category', $category)->first();

        if (!$meta) {
            $meta = Meta::create([
                'page' => PageEnumeration::$PARENT_CATEGORY,
                'category' => $category
            ]);
        }

        return view('admin.dashboard.meta.index', compact('meta'))->with('page_title', 'Meta');
    }

    public function save(Request $request) {
        $request->validate([
            'title' => 'nullable|max:500',
            'description' => 'nullable|max:500',
        ]);

        Meta::where('id', $request->meta_id)->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('message', 'Meta Saved!');
    }
}
