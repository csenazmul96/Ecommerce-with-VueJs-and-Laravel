<?php

namespace App\Http\Controllers\Admin;

use App\Model\MasterColor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Uuid;
use File;

class MasterColorController extends Controller
{
    public function index(Request $request) {

        $parameters = [];
        $appends = [];

        if ($request->s && $request->s != null) {
            $parameters[] = ['name', 'like', '%' . $request->s . '%'];
            $appends['s'] = $request->s;
        }

        $masterColors = MasterColor::where($parameters)->orderBy('name')->paginate(14);

        return view('admin.dashboard.item_settings.master_color.index', compact('masterColors'))->with('page_title', 'Master Color');
    }

    public function addPost(Request $request) {
        $request->validate([
            'color_name' => 'required|unique:master_colors,name',
            'photo' => 'nullable|mimes:jpeg,jpg,png',
            'color_code' => 'required'
        ]);

        $imagePath = null;

        if ($request->photo) {
            $filename = Uuid::generate()->string;
            $file = $request->file('photo');
            $ext = $file->getClientOriginalExtension();


            $destinationPath = '/images/master_color';

            $file->move(public_path($destinationPath), $filename.".".$ext);

            $imagePath = $destinationPath."/".$filename.".".$ext;
        }

        MasterColor::create([
            'name' => $request->color_name,
            'image_path' => $imagePath,
            'color_code' => $request->color_code,
        ]);

        return redirect()->route('admin_master_color')->with('message', 'Master Color Added!');
    }

    public function update(Request $request) {
        $request->validate([
            'color_name' => 'required|unique:master_colors,name,'.$request->colorId,
            'photo' => 'nullable|mimes:jpeg,jpg,png',
            'color_code' => 'required'
        ]);

        $color = MasterColor::where('id', $request->colorId)->first();

        $imagePath = null;

        if ($request->photo) {
            if ($color->image_path != null)
                File::delete(public_path($color->image_path));

            $filename = Uuid::generate()->string;
            $file = $request->file('photo');
            $ext = $file->getClientOriginalExtension();

            $destinationPath = '/images/master_color';

            $file->move(public_path($destinationPath), $filename.".".$ext);

            $imagePath = $destinationPath."/".$filename.".".$ext;

            $color->image_path = $imagePath;
        }

        $color->name = $request->color_name;
        $color->color_code = $request->color_code;

        $color->save();

        return redirect()->route('admin_master_color')->with('message', 'Master Color Updated!');
    }

    public function delete(Request $request) {
        $color = MasterColor::where('id', $request->id)->first();


        if ($color->image_path != null)
            File::delete(public_path($color->image_path));

        $color->delete();
    }
}
