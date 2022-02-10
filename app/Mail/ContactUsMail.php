<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ContactUsMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private $messageData;

    public function __construct($messageData)
    {
        $this->messageData = $messageData;
    }

    public function build()
    {
        return $this->subject($this->messageData['subject'])
            ->from($this->messageData['email'], $this->messageData['name'])
            ->html((new MailMessage)
                ->greeting('')
                ->line('Name: '.$this->messageData['name'])
                ->line('Email: '.$this->messageData['email'])
                ->line($this->messageData['body'])
                ->render()
            );
    }
}
