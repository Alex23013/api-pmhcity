<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Reservation;
use App\Models\User;

class ReservationDeliveredMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;
    public $user;

    public function __construct(Reservation $reservation, User $user)
    {
        $this->reservation = $reservation;
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Votre article a été livré !')
                    ->markdown('emails.reservation-delivered')
                    ->with([
                        'reservation' => $this->reservation,
                        'user' => $this->user,
                    ]);
    }
}