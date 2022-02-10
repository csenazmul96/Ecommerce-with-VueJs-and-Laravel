<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubscribeToMailchimp
{
    private $mailchimp;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(\Mailchimp $mailchimp)
    {
        $this->mailchimp = $mailchimp;
    }

    /**
     * Handle the event.
     *
     * @param  UserRegistered  $event
     * @return void
     */
    public function handle(UserRegistered $event)
    {
        $this->mailchimp->lists->subscribe(
            env('MAILCHIMP_LIST_ID'),
            ['email' => $event->user->email], 
            [
                'FNAME'  => $event->user->first_name,
                'LNAME'  => $event->user->last_name,
                'ADDRESS' => $event->user->local_address,
                'PHONE' => $event->user->phone
            ],
            null,
            false,
            true
        );
    }
}
