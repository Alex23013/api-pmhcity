<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerificationTokenMail extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $token;

    public function __construct($email, $token)
    {
        $this->email = $email;
        $this->token = $token;
    }

    public function build()
    {
        return $this->subject('Your Email Verification Code')
                    ->markdown('emails.email-verification-token')
                    ->with([
                        'email' => $this->email,
                        'token' => $this->token,
                    ]);
    }
}
