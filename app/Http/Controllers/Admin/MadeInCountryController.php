<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Model\Country;
use App\Model\MadeInCountry;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MadeInCountryController extends Controller
{
    public function madeInCountry() {
        $madeInCountries = MadeInCountry::orderBy('name')->paginate(10);
        $countries = Country::get()->toArray();

        return view('admin.dashboard.item_settings.made_in_country.index', compact('countries', 'madeInCountries'))
            ->with('page_title', 'Made In Country');
    }

    public function madeInCountryAdd(Request $request) {
        if ($request->defaultVal == '1')
            MadeInCountry::where('id', $request->id)->update([ 'default' => 0 ]);

        $country = MadeInCountry::create([
            'name' => $request->name,
            'status' => $request->status,
            'default' => $request->defaultVal,
        ]);

        return $country->toArray();
    }

    public function madeInCountryUpdate(Request $request) {

        $country = MadeInCountry::where('id', $request->id)->first();

        if ($request->defaultVal == '1')
            MadeInCountry::where('id', $request->id)->update([ 'default' => 0 ]);

        $country->name = $request->name;
        $country->status = $request->status;
        $country->default = $request->defaultVal;
        $country->save();

        return $country->toArray();
    }

    public function madeInCountryDelete(Request $request) {
        $country = MadeInCountry::where('id', $request->id)->first();
        $country->delete();
    }

    public function madeInCountryChangeStatus(Request $request) {
        $country = MadeInCountry::where('id', $request->id)->first();
        $country->status = $request->status;
        $country->save();
    }

    public function madeInCountryChangeDefault(Request $request) {
        MadeInCountry::where('id', $request->id)->update([ 'default' => 0 ]);
        $country = MadeInCountry::where('id', $request->id)->first();
        $country->default = 1;
        $country->save();
    }
}
