<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordTokenMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $user;

    public function __construct($user, $token)
    {
        $this->token = $token;
        $this->user = $user;
    }

    
    public function build()
    {
        $url = 'https://pmhcity-preview.angeljsd.dev/new-password/' . $this->token;

        return $this->subject('Reset Your Password')
            ->view('emails.forgot-password')
            ->with([
                'url' => $url,
                'name' => $this->user->name ?? $this->user->email,
            ]);
    }
}
