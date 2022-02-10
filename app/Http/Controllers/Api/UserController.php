<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use App\Model\User;
use App\Model\MetaBuyer;
use App\Model\CartItem;
use App\Model\BuyerShippingAddress;
use App\Enumeration\Role;

use Session;
use Illuminate\Support\Facades\Hash;
use Uuid;
use Mail;
use Carbon\Carbon;
use App\Model\LoginHistory;
use App\Model\WishListItem;

class UserController extends Controller
{
    public function index(){

        $user = Auth::user();

        return response()->json(['user' => $user], 200);

    }

    public function users(){

        $users = User::with(['buyer'])->get();

        return response()->json(['users_count' => count($users), 'users' => $users], 200);

    }

    public function register(Request $request){

        $this-> validate($request,[
            'firstName' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:6'
        ]);

        $meta = MetaBuyer::create([
            'verified' => 0,
            'active' => 0,
            'user_id' => 0,
            'receive_offers' =>0,
        ]);

        $user = User::create([
            'first_name' => $request->firstName,
            'email' => $request->email,
            'last_name' => $request->lastName,
            'password' => Hash::make($request->password),
            'role' => Role::$BUYER,
            'buyer_meta_id' => $meta->id,
        ]);

        $meta->user_id = $user->id;
        $meta->save();


         $user = User::where([
            'email' =>$request->email,
        ])
        ->with('buyer')
        ->first();

        $response = [
            'status_code' => '200',
            'message' => 'Registration Successful.',
            'user' => $user,
            'token' => $user->createToken('LynktoORCS'.$request->email)->accessToken,
        ];

        return response()->json($response, 200);

    }

    public function login(Request $request){
        $rules['email'] = 'required|email';
        $rules['password'] = 'required';
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->messages()], 200);
        }else{
            if ( Auth::attempt( ['email' => $request->email, 'password' => $request->password] ) ) {
                $user = User::where([
                    'email' =>$request->email,
                    'active' => 1
                ])
                ->with('buyer')->first();
                if(!empty($user)){
                    $response = [
                        'status_code' => '200',
                        'user' => $user,
                        'token' => Auth::user()->createToken('LynktoORCS'.$request->email.$request->password)->accessToken,
                    ];
                    $this->mergeGuestToAuth($request->ip());
                    return response()->json($response, 200);
                }else{
                    return response()->json(['status_code' => '404','error' => 'Inactive User.'], 404);
                }

            }else{
                $validator->errors()->add('notfound', 'Email Or Password Incorrect.');
                return response()->json(['error'=>$validator->messages()], 200);
            }
        }

    }



    public function buyerlogout()
    {
       return Auth::user();
    }
    public function mergeGuestToAuth($ip){
        CartItem::where('user_id', $ip)->update(['user_id' => Auth::user()->id]);
        WishListItem::where('user_id', $ip)->update(['user_id' => Auth::user()->id]);
        return true;
    }



    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'LogOut Successfully!!','status'=>'usernotfound'], 200);
    }
    public function ChangePassword(Request $request){

        $request->validate([
            'oldpassword' => 'required' ,
            'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
        ]);

        $user = Auth::user();
        if (!$user){
            return response()->json(['message' => 'User Not Found'], 200);
        } else{
            if (!Hash::check($request->oldpassword, $user->password)) {
                return response()->json(['oldpassword' => 'Old Password Not Metch.','status'=>'faield'], 200);
            }else{
                $user->password = Hash::make($request->password);
                $user->reset_token = $request->token;
                $user->save();
                return response()->json(['message' => 'Password Updated Successfully.','status'=>'success'], 200);
            }
        }
    }
    public function ProfileUpdate(Request $request){
        $user = Auth::user();
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|string|email|max:255|unique:users, email,'.$this->user->id ,
            'billing_phone' => 'required'
        ]);

        $user->load('buyer');
        $user->buyer->billing_phone = $request->billing_phone;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->email = $request->email;
        $user->save();
        $user->buyer->save();
    }
    public function resetpassword(Request $request){
        $request->validate([
            'email' => 'email|required|exists:users,email',
        ]);
        $user = User::where('role', Role::$BUYER)->where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['errors' => ['email' => ['Email not found.']]], 422);
        }

        $token = Uuid::generate()->string;

        $user->reset_token = $token;
        $user->save();
        // is not completed yet. need next action implement.

        // Mail::send('emails.buyer.password_reset', ['token' => $token], function ($message) use ($user) {
        //     $message->subject('Reset Password');
        //     $message->to($user->email, $user->first_name.' '.$user->last_name);
        // });
        return response()->json(['message' => 'Password Reset Link Sended To This Email.','status'=>'success'], 200);
    }

    public function newpassword(Request $request){
        $request->validate([
            'password' => 'required|min:6|confirmed'
        ]);
        $user = User::where('role', Role::$BUYER)->where('reset_token', $request->token)->first();
        if (!$user)
            return response()->json(['message' => 'User not found','status'=>'failed'], 200);
        $user->password = Hash::make($request->password);
        $user->reset_token = null;
        $user->save();
        return response()->json(['message' => 'Password Reset Successfully!','status'=>'success'], 200);
    }

    public function checkEmail(Request $request)
    {
        return User::where('email', $request->email)->first();
    }

}
