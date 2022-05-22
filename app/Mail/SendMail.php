<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;
    public $sendUsers,$sendContact,$receiver;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($sendUsers,$sendContact,$receiver)
    {
        $this->sendUsers = $sendUsers;
        $this->sendContact = $sendContact;
        $this->receiver = $receiver;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('contact.contact-mail')->subject('sharing Contact');
    }
}
