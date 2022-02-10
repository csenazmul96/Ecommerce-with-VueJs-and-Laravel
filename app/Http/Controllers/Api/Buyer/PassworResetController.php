<?php

namespace App\Http\Controllers\Api\Buyer;

use App\Model\User;
use App\Enumeration\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Notifications\MailResetPasswordNotification;

class PassworResetController extends Controller
{
    
    public function sendPasswordResetLink(Request $request)
    {
        
        $request->validate([
            'email' => 'email|required|exists:users,email',
        ]);

        $user = User::where('role', Role::$BUYER)->where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['errors' => ['email' => ['Email not found.']]], 422);
        }else{
            $token = Hash::make($request->email);
            $user->update(['reset_token' => $token]);

            $user->notify(new MailResetPasswordNotification($token));
        }

        return response()->json(['message' => 'Email Successfully sent to this email address.']);
    }
}
