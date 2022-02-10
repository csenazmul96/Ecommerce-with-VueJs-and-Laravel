<?php

namespace App\Http\Controllers\Api\Buyer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class FacebookController extends Controller
{
	
    /**
     * Redirect the user to the social provider authentication page.
     *
     * @return Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Obtain the user information from social provider.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();
        } catch (Exception $e) {
            return Redirect::to('/auth/login');
        }

        $authUser = $this->findOrCreateUser($user);

        auth()->login($authUser, true);

        return redirect()->to('/');
    }

    /**
     * Return user if exists; create and return if doesn't
     *
     * @param $socialLiteUser
     * @return User
     */
    private function findOrCreateUser($socialLiteUser)
    {

        $user = User::firstOrNew([
            'email' => $socialLiteUser->email,
        ], [
            'facebook_id' => $socialLiteUser->id,
            'name' => $socialLiteUser->name
        ]);


        return $user;
    }

    public function authenticateUser()
    {
        /**
         * If there is a user already logged in, then we shouldn't 
         * login him out!
         */
        if (auth()->check()) {
            return response()->json([
                'status'  => false,
                'message' => 'User already logged in'
            ]);
        }

        $id = request('id');

        $user = User::where('facebook_id', $id)->first();

        if (count($user) && auth()->loginUsingId($user->id)) {
            return response()->json([
                'status' => true,
                'user'   => $user
            ]);
        }

        return response()->json([
            'status' => false
        ]);
    }
}