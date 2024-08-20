<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class InternalMail extends Mailable
{
    use Queueable, SerializesModels;
    public $mailData, $to_name, $from_name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailData)
    {
        $this->mailData = $mailData;        
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            from: $this->mailData['sender'],
            subject: $this->mailData['subject'],
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        $this->mailData['sender_name'] = Auth::user()->name != '' ? Auth::user()->name.' '.Auth::user()->last_name : '';        
        $this->mailData['recipient_name'] = User::whereEmail($this->mailData['recipient'])->first()->toArray()['name'] != '' ? User::whereEmail($this->mailData['recipient'])->first()->toArray()['name'].' '.User::whereEmail($this->mailData['recipient'])->first()->toArray()['last_name'] : '';
        return new Content(
            view: 'mails.internal-mail',
            text: 'mails.internal-mail-plaintext',
            with: [                
                'body' => $this->mailData['message'],
                'links' => $this->mailData['links'],
                'sender_name' => $this->mailData['sender_name'],
                'recipient_name' => $this->mailData['recipient_name'],
            ],
        );
        
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
