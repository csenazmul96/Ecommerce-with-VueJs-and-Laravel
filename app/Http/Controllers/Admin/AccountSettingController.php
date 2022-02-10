<?php

namespace App\Http\Controllers\Admin;

use DB;
use App\Model\User;
use App\Model\Order;
use App\Enumeration\Role;
use App\Model\MetaVendor;
use App\Model\AdminMessage;
use App\Model\LoginHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountSettingController extends Controller
{
    public function index() {
        $user = Auth::user();
        $user->load('vendor');
        $usersOfVendor = [$user->id];

        $users = User::where('id', '!=', $user->id)->where('role', Role::$EMPLOYEE)->get();

        foreach($users as &$item) {
            $item->permissions = $item->permissions();
            $usersOfVendor[] = $item->id;
        }

        $loginHistory = LoginHistory::whereIn('user_id', $usersOfVendor)
            ->orderBy('created_at', 'desc')->with('user')->paginate(10);

        return view('admin.dashboard.administration.account_setting.index', compact('user', 'users', 'loginHistory'))
            ->with('page_title', 'Account Setting');
    }

    public function adminIdPost(Request $request) {
        $user = Auth::user();

        $user->first_name = $request->firstName;
        $user->last_name = $request->lastName;

        if ($request->password != '')
            $user->password = Hash::make($request->password);

        $user->save();
    }

    public function addAccountPost(Request $request) {
        $user = User::where('user_name', $request->userId)->first();

        if ($user)
            return response()->json(['success' => false, 'message' => 'Already exists this User ID.']);

        $user = User::create([
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'user_name' => $request->userId,
            'password' => Hash::make($request->password),
            'role' => Role::$EMPLOYEE,
            'active' => $request->status
        ]);

        if ($request->permissions) {
            foreach ($request->permissions as $permission) {
                DB::table('user_permission')->insert([
                    'user_id' => $user->id,
                    'permission' => $permission
                ]);
            }
        }

        $user->permissions = $user->permissions();

        return response()->json(['success' => true, 'message' => $user->toArray()]);
    }

    public function deleteAccountPost(Request $request) {
        $user = User::where('id', $request->id)->first();
        $user->delete();
    }

    public function updateAccountPost(Request $request) {
        $user = User::where('user_name', $request->userId)
            ->where('id', '!=', $request->id)
            ->where('vendor_meta_id', Auth::user()->vendor_meta_id)
            ->where('role', Role::$VENDOR_EMPLOYEE)->first();

        if ($user)
            return response()->json(['success' => false, 'message' => 'User Name already taken.']);

        $user = User::where('id', $request->id)->first();

        $user->first_name = $request->firstName;
        $user->last_name = $request->lastName;
        $user->user_name = $request->userId;
        $user->active = $request->status;

        if ($request->password != '') {
            $user->password = Hash::make($request->password);
        }

        $user->save();


        DB::table('user_permission')->where('user_id', $request->id)->delete();
        
        if ($request->permissions) {
            foreach ($request->permissions as $permission) {
                DB::table('user_permission')->insert([
                    'user_id' => $user->id,
                    'permission' => $permission
                ]);
            }
        }

        $user->permissions = $user->permissions();

        return response()->json(['success' => true, 'message' => $user->toArray()]);
    }

    public function statusUpdateAccountPost(Request $request) {
        User::where('id', $request->id)->update(['active' => $request->status]);
    }

    public function saveStoreSetting(Request $request) {
        $user = User::where('id', Auth::user()->id)->with('vendor')->first();

        $user->vendor->setting_not_logged = $request->que1;
        $user->vendor->setting_unverified_user = $request->que2;
        $user->vendor->setting_unverified_checkout = $request->que3;
        $user->vendor->setting_sort_activation_date = $request->que4;
        $user->vendor->setting_consolidation = $request->que5;
        $user->vendor->setting_estimated_shipping_charge = $request->que6;

        $user->vendor->save();
    }

    public function allMessage(){
        $messages = AdminMessage::with(['user','user.buyer'])->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.dashboard.administration.message.index', compact('messages'))
            ->with('page_title', 'All Message');
    }

    public function allMessageStatus(Request $request)
    {
        AdminMessage::where('id', $request->id)
            ->update(['reading_status' => 1]);
        return response()->json(['success' => true, 'message' => 'Success']);

    }
}
