<?php

namespace App\Http\Controllers\Admin;

use App\Model\SkinType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SkinTypesController extends Controller
{
    public function skinTypes(){
        $skinTypes = SkinType::paginate(10);
        return view('admin.dashboard.administration.skin_types.index', compact('skinTypes'))->with('page_title', 'Skin Types');
    }

    public function skinTypesAdd(Request $request){
        $request->validate([
            'type' => 'required'
        ]);

        SkinType::create([
            'type' => $request->type
        ]);

        return redirect()->route('admin_skin_types')->with('message', 'Skin Types Added!');
    }

    public function skinTypesUpdate(Request $request) {
        $request->validate([
            'type' => 'required'
        ]);

        SkinType::where('id', $request->skinTypeId)->update([
            'type' => $request->type
        ]);

        return redirect()->route('admin_skin_types')->with('message', 'Skin Types Updated!');
    }

    public function skinTypesDelete(Request $request) {
        SkinType::where('id', $request->id)->delete();
    }
}
