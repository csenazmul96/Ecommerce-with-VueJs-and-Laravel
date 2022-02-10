<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Recaptcha implements Rule
{
    const URL = 'https://www.google.com/recaptcha/api/siteverify';

    public function __construct()
    {
        //
    }

    public function passes($attribute, $value)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->post(
            static::URL,
            array(
                'form_params' => array(
                     'secret' => config('services.recaptcha.secret'),
                    'response' => $value,
                    'remoteip' => request()->ip()
                )
            )
        );
        
        if($response->getStatusCode() == '200'){
            return response()->json(['success' => 'success'], 200);
        }
    }

    public function message()
    {
        return 'The validation error message.';
    }
}
