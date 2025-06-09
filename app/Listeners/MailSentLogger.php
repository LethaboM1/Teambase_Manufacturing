<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailSentLogger
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
     * @param  \Illuminate\Mail\Events\MessageSent  $event
     * @return void
     */
    public function handle(MessageSent $event)
    {
        //Log Mails to Log file
        $message = $event->message;
        // dd($message);
        $to = implode(', ', array_keys($message->getTo()));
        $cc = implode(', ', array_keys($message->getCc()));
        $bcc = implode(', ', array_keys($message->getBcc()));
        $subject = $message->getSubject();
        // $body = $message->getBody();

        Log::info("Email sent to: $to; CC: $cc; BCC: $bcc; Subject: $subject");
        // dd($message);
    }
}
