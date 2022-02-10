<?php

namespace App\Http\Controllers\Admin;

use App\Enumeration\Role;
use App\Model\LoginHistory;
use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Auth;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function login() {
        return view('admin.auth.login');
    }

    public function loginPost(Request $request) {
        $user = User::where('user_name', $request->username)
            ->whereIn('role', [Role::$ADMIN, Role::$EMPLOYEE])
            ->first();

        if (!$user)
            return redirect()->route('login_admin')->with('message', 'Username not found.')->withInput();

        if ($user->active == 0)
            return redirect()->route('login_admin')->with('message', 'Username not active.')->withInput();

        if (Hash::check($request->password, $user->password)) {
            if ($request->remember_me)
                Auth::login($user, true);
            else
                Auth::login($user);

            $user->last_login = Carbon::now()->toDateTimeString();
            $user->save();

            LoginHistory::create([
                'user_id' => $user->id,
                'ip' => $request->ip()
            ]);

            return redirect()->route('admin_dashboard');
        }

        return redirect()->route('login_admin')->with('message', 'Invalid Password.')->withInput();
    }

    public function logout() {
        Auth::logout();

        return redirect()->route('login_admin');
    }
}
