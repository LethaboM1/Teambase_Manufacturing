<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MailLogger
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \Illuminate\Mail\Events\MessageSending  $event
     * @return void
     */
    public function handle(MessageSending $event)
    {
        //Log Mails to Log file
        $message = $event->message;
        // dd($message);
        $to = implode(', ', array_keys($message->getTo()));
        $cc = implode(', ', array_keys($message->getCc()));
        $bcc = implode(', ', array_keys($message->getBcc()));
        $subject = $message->getSubject();
        // $body = $message->getBody();

        Log::info("Email sending to: $to; CC: $cc; BCC: $bcc; Subject: $subject");
        // dd($message);
    }    
}
