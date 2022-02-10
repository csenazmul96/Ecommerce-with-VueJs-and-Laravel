<?php

namespace App\Http\Controllers\Api\Buyer;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Model\User;
use App\Model\MetaBuyer;
use App\Model\LoginHistory;
use Illuminate\Http\Request;
use Mail;
use App\Model\CartItem;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'socialLogin', 'me']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {

        $request = request();
        $request->validate([
            'email' => 'required|string|email|exists:users,email',
            'password' => 'required|string',
        ]);

        $credentials = request(['email', 'password']);

        if ($this->guard()->attempt($credentials)) {
            if($this->guard()->user()->active == 0) {
                $token = $this->guard()->attempt($credentials);
                if($request->guest_id)
                    $this->addToGustCart($request->guest_id, $this->guard()->user()->id);

                return $this->respondWithToken($token);
            } else {
                return response()->json(['errors' => ['email' => ['user is blocked.']]], 422);
            }
        } else {
            return response()->json(['errors' => ['email' => ['Email and password did not match.']]], 422);
        }

        /*if (! $token = $this->guard()->attempt($credentials)) {
            return response()->json(['errors' => ['email' => ['Email and password did not match.']]], 422);
        }



        return $this->respondWithToken($token);*/

    }

    public function socialLogin(Request $request) {

        $request->validate([
            'provider' => 'required',
            'providerId' => 'required',
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email',
        ]);

        $existingUser = User::where('provider_id', $request->providerId)->orWhere('email', $request->email)->first();

        if ($existingUser) {
            $token = $this->guard()->login($existingUser);

            if($request->guest_id)
                $this->addToGustCart($request->guest_id, $existingUser->id);

            return $this->respondWithToken($token);
        } else {

            $metaBuyer = MetaBuyer::create([
                'verified' => 0,
                'active' => 0,
                'user_id' => 0,
                'receive_offers' =>0,
            ]);

            $user = User::create([
                'first_name' => $request->firstName,
                'email' => $request->email,
                'last_name' => $request->lastName,
                'role' => \App\Enumeration\Role::$BUYER,
                'buyer_meta_id' => $metaBuyer->id,
                'provider' => $request->provider,
                'provider_id' => $request->providerId,
            ]);

            $metaBuyer->user_id = $user->id;
            $metaBuyer->save();

            $token = $this->guard()->login($user);
            if($request->guest_id)
                $this->addToGustCart($request->guest_id, $user->id);

            return $this->respondWithToken($token);
        }
    }

    public function addToGustCart($guest_id, $user_id) {
        $carts = CartItem::where('user_id', $guest_id)->get();
        foreach($carts as $item){
            $item->user_id = $user_id;
            $item->save();
        }
    }

    /**
     * Get a JWT via given credentials. (Register)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(){
        $request = request();
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:6'
        ]);

        $metaBuyer = MetaBuyer::create([
            'verified' => 0,
            'active' => 0,
            'user_id' => 0,
            'receive_offers' =>0,
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'email' => $request->email,
            'active' => 0,
            'last_name' => $request->last_name,
            'password' => bcrypt($request->password),
            'role' => \App\Enumeration\Role::$BUYER,
            'buyer_meta_id' => $metaBuyer->id,
        ]);

        $metaBuyer->user_id = $user->id;
        $metaBuyer->save();

        if($request->guest_id)
            $this->addToGustCart($request->guest_id, $user->id);

        //Send Mail to User
        Mail::send('emails.buyer.registration_complete', ['user' => $user], function ($message) use ($request) {
            $message->subject('Registration Complete');
            $message->to($request->email, $request->first_name.' '.$request->last_name);
        });

        return $this->login();

    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        if ($this->guard()->check()) {
            return response()->json(['logged' => true], 200);
        } else {
            return response()->json(['logged' => false], 200);
        }

        //return response()->json($this->guard()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $login = new LoginHistory();
        $login->user_id =$this->guard()->user()->id;
        $login->ip = request()->ip();
        $login->save();

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => $this->guard()->user()->only('name', 'email'),
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

    public function guard(){
        return Auth::Guard('api');
    }
}
