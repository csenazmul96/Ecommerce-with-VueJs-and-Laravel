<?php

namespace App\Http\Controllers\Buyer;

use App\Enumeration\Role;
use App\Model\BuyerMessage;
use App\Model\Country;
use App\Model\LoginHistory;
use App\Model\MetaBuyer;
use App\Model\State;
use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Uuid;
use Carbon\Carbon;
use URL;
use Mail;
use App\Events\UserRegistered;
use Session;
use  Spatie\Newsletter\NewsletterFacade as Newsletter;

class AuthController extends Controller
{
    public function resetPasswordPost(Request $request) {
        $request->validate([
            'email' => 'required'
        ]);
        $user = User::where('role', Role::$BUYER)->where('email', $request->email)->first();

        if (!$user)
            return redirect()->back()->with('message', 'Email Not Found.')->withInput();

        $token = Uuid::generate()->string;

        $user->reset_token = $token;
        $user->save();

        Mail::send('emails.buyer.password_reset', ['token' => $token], function ($message) use ($user) {
            $message->subject('Reset Password');
            $message->to($user->email, $user->first_name.' '.$user->last_name);
        });

        return redirect()->back()->with('message', 'Email has sent with reset password link.');
    }


    public function newPassword(Request $request)
    {
        if ($request->token) {
            $user = User::where('role', Role::$BUYER)->where('reset_token', $request->token)->first();
            if (!$user)
                abort(404);
            return view('buyer.auth.new_password');
        } else {
            abort(404);
        }
    }

    public function newPasswordPost(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::where('role', Role::$BUYER)->where('reset_token', $request->token)->first();

        if (!$user)
            abort(404);

        $user->password = Hash::make($request->password);
        $user->reset_token = null;
        $user->save();

        return redirect()->route('buyer_login',compact('user'));
    }

    public function newBuyerPassword(Request $request)
    {
        if(Auth::user()){
            $data['unread_messages'] = BuyerMessage::where('user_id', Auth::user()->id)
                ->where('reading_status', 0)
                ->count();
        }
        $data['buyerAvatar'] = MetaBuyer::where('user_id', auth()->user()->id)->first();
        return view('buyer.profile.new_password',$data);
    }

    public function newBuyerPasswordPost(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::where('role', Role::$BUYER)->where('id', auth()->user()->id)->first();

        if ( ! $user ){
            abort(404);
        }
        $user->password = Hash::make($request->password);
        $user->reset_token = $request->token;
        $user->save();

        return redirect()->back()->with('flash_message_success', 'Password Updated Successfully.');
    }
}
