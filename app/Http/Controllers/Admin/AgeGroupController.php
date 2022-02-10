<?php

namespace App\Http\Controllers\Admin;

use App\Model\AgeGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AgeGroupController extends Controller
{
    public function ageGroup(){
        $ageGroups = AgeGroup::paginate(10);
        return view('admin.dashboard.administration.age_group.index', compact('ageGroups'))->with('page_title', 'Age Group');
    }

    public function ageGroupAdd(Request $request){
        $request->validate([
            'lower_limit' => 'required|integer',
            'upper_limit' => 'required|integer|gt:lower_limit'
        ]);

        $ageGroup = AgeGroup::create([
            'lower_limit' => $request->lower_limit,
            'upper_limit' => $request->upper_limit
        ]);

        return $ageGroup->toArray();
    }

    public function ageGroupUpdate(Request $request) {
        $request->validate([
            'lower_limit' => 'required|integer',
            'upper_limit' => 'required|integer|gt:lower_limit'
        ]);

        $ageGroup = AgeGroup::where('id', $request->id)->first();

        $ageGroup->lower_limit = $request->lower_limit;
        $ageGroup->upper_limit = $request->upper_limit;
        $ageGroup->save();

        return $ageGroup->toArray();

        return redirect()->route('admin_age_group')->with('message', 'Age Group  Updated!');
    }

    public function ageGroupDelete(Request $request) {
        $ageGroup = AgeGroup::where('id', $request->id)->first();
        $ageGroup->delete();
    }
}
