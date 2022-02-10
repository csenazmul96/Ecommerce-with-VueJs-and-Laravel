<?php

namespace App\Http\Controllers\Api\Buyer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Hash;
use Str;
use App\Model\User;
use App\Enumeration\Role;

class NewPasswordController extends Controller
{
    public function callResetPassword(Request $request)
    {
        $request->validate([
            'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:6'
        ]);

        $user = User::where('reset_token',$request->token)->first();

        if($user){
            $user->reset_token = 'NULL';
            $user->password = Hash::make($request->password);
            $user->save();
            
            return response()->json(['message' => 'Your Password Reset Successfully! Now you can Login']);
        }else{
            return response()->json(['message' => 'Reset token Expire or not match with this user.']);
        }
    }
}
