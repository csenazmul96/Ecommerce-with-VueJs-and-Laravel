<?php

namespace App\Http\Controllers\Api;

use App\Model\MetaBuyer;
use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Newsletter\Newsletter;
use Illuminate\Support\Facades\Auth;
use DB;
use DrewM\MailChimp\MailChimp;
use Spatie\Newsletter\NewsletterListCollection;

class NewsletterController extends Controller
{
    public function addNewsletter(Request $request) {
        $request->validate([
            'email' => 'required|string|email',
        ]);

        if(auth()->user()){
            $mailChimp = new MailChimp(env('MAILCHIMP_APIKEY'));

            $config['defaultListName'] = env('MAILCHIMP_EMAIL_LIST');
            $config['lists'] = [
                env('MAILCHIMP_EMAIL_LIST') => [
                    'id' => env('MAILCHIMP_LIST_ID'),
                ],
            ];
            $list = NewsletterListCollection::createFromConfig($config);

            $NewsLetter = new Newsletter($mailChimp , $list);

            if($request->email) {
                $NewsLetter->subscribe($request->email);
                $signup_email = User::where('email', $request->email)->first();

                if($signup_email){
                    $buyers = MetaBuyer::where('user_id', $signup_email->id)->first();
                    $buyers->mailing_list = 1;
                    $buyers->save();
                }
                $metaBuyer = DB::table('meta_buyers')->where('user_id', auth()->user()->id)->first();

                if($metaBuyer->mailing_list == 0){
                    DB::table('meta_buyers')
                        ->where('user_id', auth()->user()->id)
                        ->update(['mailing_list' => 1]);
                }
            }

            return response()->json(['message' => 'Thank you for your concern. We recieved your request.','status'=>'success'], 200);
        }
        else{
            $mailChimp = new MailChimp(env('MAILCHIMP_APIKEY'));

            $config['defaultListName'] = env('MAILCHIMP_EMAIL_LIST');
            $config['lists'] = [
                env('MAILCHIMP_EMAIL_LIST') => [
                    'id' => env('MAILCHIMP_LIST_ID'),
                ],
            ];
            $list = NewsletterListCollection::createFromConfig($config);

            $NewsLetter = new Newsletter($mailChimp , $list);
            if($request->email) {
                $status = $NewsLetter->subscribe($request->email);
                $signup_email = User::where('email', $request->email)->first();

                if($signup_email){
                    $buyers = MetaBuyer::where('user_id', $signup_email->id)->first();
                    $buyers->mailing_list = 1;
                    $buyers->save();
                }
            }
            return response()->json(['message' => 'Thank you for your concern. We recieved your request.','status'=>'success'], 200);
        }
    }
    public function newsletterUpdate(Request $request) {

        if(auth()->user()) {
            $mailChimp = new MailChimp(env('MAILCHIMP_APIKEY'));

            $config['defaultListName'] = env('MAILCHIMP_EMAIL_LIST');
            $config['lists'] = [
                env('MAILCHIMP_EMAIL_LIST') => [
                    'id' => env('MAILCHIMP_LIST_ID'),
                ],
            ];
            $list = NewsletterListCollection::createFromConfig($config);

            $NewsLetter = new Newsletter($mailChimp, $list);
            $NewsLetter->unsubscribe(auth()->user()->email);

            $signup_email = User::where('email', auth()->user()->email)->first();
            if ($signup_email) {
                $buyers = MetaBuyer::where('user_id', $signup_email->id)->first();
                $buyers->mailing_list = 0;
                $buyers->save();
            }

        }
    }
}
