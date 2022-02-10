<?php

namespace App\Http\Controllers\Admin;

use App\Model\Color;
use App\Model\MasterColor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Uuid;
use Auth;
use File;
use Image;

class ColorController extends Controller
{
    public function index(Request $request) {

        $parameters = [];
        $appends = [];

        if ($request->s && $request->s != null) {
            $parameters[] = ['name', 'like', '%' . $request->s . '%'];
            $appends['s'] = $request->s;
        }

        $colors = Color::where($parameters)->orderBy('name')->paginate(49);
        $masterColors = MasterColor::orderBy('name')->get();

        return view('admin.dashboard.item_settings.color.index', compact('masterColors', 'colors', 'appends'))->with('page_title', 'Color');
    }

    public function addPost(Request $request) {

        $inputArray = array(
            'name' => $request->color_name,
            'status' => $request->status,
            'master_color_id' => $request->master_color,
            'photo' => $request->photo,
        );

        $request->validate([
            'color_name' => 'required',
            'master_color' => 'required',
            'photo' => 'nullable|mimes:jpeg,jpg,png',
            'color_code' => 'required'
        ]);

        $imagePath = null;
        $thumbsPath = null;

        if ($request->photo) {
            $filename = Uuid::generate()->string;
            $file = $request->file('photo');
            $ext = $file->getClientOriginalExtension();

            $destinationPath = '/images/colors';
            $thumbsPath = '/images/colors/thumbs/'.$filename.'.'.$ext;

            $thumb = Image::make($file->getRealPath())->resize(40, 40);
            $thumb->save(public_path($thumbsPath), 60);

            $file->move(public_path($destinationPath), $filename.".".$ext);

            $imagePath = $destinationPath."/".$filename.".".$ext;
        }

        Color::create([
            'name' => $request->color_name,
            'status' => $request->status,
            'master_color_id' => $request->master_color,
            'image_path' => $imagePath,
            'thumbs_image_path' => $thumbsPath,
            'color_code' => $request->color_code,
        ]);

        return redirect()->route('admin_color')->with('message', 'Color Added!');
    }

    public function editPost(Request $request) {

        $request->validate([
            'color_name' => 'required',
            'master_color' => 'required',
            'photo' => 'nullable|mimes:jpeg,jpg,png',
            'color_code' => 'required'
        ]);

        $color = Color::where('id', $request->colorId)->first();

        $imagePath = null;
        $thumbsPath = null;

        if ($request->photo) {
            if ($color->image_path != null)
                File::delete(public_path($color->image_path));
            if ($color->thumbs_image_path != null)
                File::delete(public_path($color->thumbs_image_path));

            $filename = Uuid::generate()->string;
            $file = $request->file('photo');
            $ext = $file->getClientOriginalExtension();

            $destinationPath = '/images/colors';
            $thumbsPath = '/images/colors/thumbs/'.$filename.'.'.$ext;

            // Thumbs Image
            $thumb = Image::make($file->getRealPath())->resize(40, 40);
            $thumb->save(public_path($thumbsPath), 60);

            $file->move(public_path($destinationPath), $filename.".".$ext);

            $imagePath = $destinationPath."/".$filename.".".$ext;

            $color->image_path = $imagePath;
            $color->thumbs_image_path = $thumbsPath;
        }

        $color->name = $request->color_name;
        $color->color_code = $request->color_code;
        $color->status = $request->status;
        $color->master_color_id = $request->master_color;
        $color->save();
        $color->touch();

        return redirect()->route('admin_color')->with('message', 'Color Updated!');
    }

    public function delete(Request $request) {
        $color = Color::where('id', $request->id)->first();

        if ($color->image_path != null)
            File::delete(public_path($color->image_path));

        $color->delete();
    }
}
