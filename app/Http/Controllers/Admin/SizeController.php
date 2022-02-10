<?php

namespace App\Http\Controllers\Admin;

use App\Model\Size;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SizeController extends Controller
{
    public function size(){
        $sizes = Size::orderBy('created_at', 'DESC')->paginate(10);
        return view('admin.dashboard.item_settings.size.index', compact('sizes'))->with('page_title', 'Sizes');
    }

    public function sizeAdd(Request $request){
        $request->validate([
            'name' => 'required',
            'symbol' => 'required'
        ]);

        $size = Size::create([
            'name' 	=> $request->name,
            'item_size' => $request->symbol,
            'desc' 	=> $request->description ,
            'status' => $request->status
        ]);

        return $size->toArray();
    }

    public function sizeUpdate(Request $request) {
        
        $request->validate([
            'name' => 'required',
            'symbol' => 'required'
        ]);

        $size = Size::where('id', $request->id)->first();
 
        $size->name = $request->name;
        $size->item_size = $request->symbol;
        $size->desc = $request->description;
        $size->status = $request->status; 
        $size->save();

        return $size->toArray();
    }

    public function sizeDelete(Request $request) {
        $size = Size::where('id', $request->id)->first();
        $size->delete();
    }

    public function sizeChangeStatus(Request $request) {
        $size = Size::where('id', $request->id)->first();
        $size->status = $request->status;
        $size->save();
    }
}
