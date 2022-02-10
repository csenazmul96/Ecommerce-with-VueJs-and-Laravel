<?php

namespace App\Http\Controllers\Admin;

use File;
use Uuid;
use App\Model\ItemValue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ItemValueController extends Controller
{
    public function index(){
        $itemValues = ItemValue::paginate(10);
        return view('admin.dashboard.item_settings.item_values.index', compact('itemValues'))->with('page_title', 'Item Values');
    }

    public function itemValueAdd(Request $request){

        $request->validate([
            'value_name' => 'required|unique:item_values,name|max:30',
            'value_description ' => 'nullable|max:350',
            'icon' => 'required',
            'photo' => 'nullable|mimes:jpeg,jpg,png'
        ]);

        $imagePath = null;

        if ($request->photo) {
            $filename = Uuid::generate()->string;
            $file = $request->file('photo');
            $ext = $file->getClientOriginalExtension();


            $destinationPath = '/images/item_values';

            $file->move(public_path($destinationPath), $filename.".".$ext);

            $imagePath = $destinationPath."/".$filename.".".$ext;
        }

        ItemValue::create([
            'icon' => $request->icon,
            'name' => $request->value_name,
            'description' => $request->value_description,
            'link' => $request->value_link,
            'status' => $request->statusValue,
            'image_path' => $imagePath,
        ]);

        return redirect()->route('admin_item_values')->with('message', 'Item Value Added!');
    }

    public function itemValueUpdate(Request $request) {
        $request->validate([
            'value_name' => 'required|max:30|unique:item_values,name,'.$request->valueId,
            'value_description ' => 'nullable|max:350',
            'icon' => 'required',
            'photo' => 'nullable|mimes:jpeg,jpg,png'
        ]);

        $itemValue = ItemValue::where('id', $request->valueId)->first();

        $imagePath = null;

        if ($request->photo) {
            if ($itemValue->image_path != null)
                File::delete(public_path($itemValue->image_path));

            $filename = Uuid::generate()->string;
            $file = $request->file('photo');
            $ext = $file->getClientOriginalExtension();

            $destinationPath = '/images/item_values';

            $file->move(public_path($destinationPath), $filename.".".$ext);

            $imagePath = $destinationPath."/".$filename.".".$ext;

            $itemValue->image_path = $imagePath;
        }

        $itemValue->icon = $request->icon;
        $itemValue->name = $request->value_name;
        $itemValue->description = $request->value_description;
        $itemValue->link = $request->value_link;
        $itemValue->status = $request->statusValue;

        $itemValue->save();

        return redirect()->route('admin_item_values')->with('message', 'Item Value Updated!');
    }

    public function itemValueDelete(Request $request) {
        $itemValue = ItemValue::where('id', $request->id)->first();
        // if ($itemValue->icon != null)
        //     File::delete(public_path($itemValue->icon));
        $itemValue->delete();
    }
}
