<?php

namespace App\Http\Controllers\Admin;

use App\Model\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BrandController extends Controller
{
    public function brands(){
        $brands = Brand::paginate(10);
        return view('admin.dashboard.item_settings.brand.index', compact('brands'))->with('page_title', 'Brands');
    }

    public function brandsAdd(Request $request){
        $request->validate([
            'name' => 'required'
        ]);

        $brand = Brand::create([
            'name' => $request->name,
            'status' => $request->status
        ]);

        return $brand->toArray();
    }

    public function brandsUpdate(Request $request) {
        $brand = Brand::where('id', $request->id)->first();

        $brand->name = $request->name;
        $brand->status = $request->status;
        $brand->save();

        return $brand->toArray();
    }

    public function brandsDelete(Request $request) {
        $brand = Brand::where('id', $request->id)->first();
        $brand->delete();
    }

    public function brandsChangeStatus(Request $request) {
        $brand = Brand::where('id', $request->id)->first();
        $brand->status = $request->status;
        $brand->save();
    }
}
