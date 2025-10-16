<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Reservation;
use App\Models\Parameter;

class ReservationPaidMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function build()
    {
        $deliveryPrice = round(Parameter::where('name', 'pmh_relay_delivery_price')->first()?->value, 2);
        return $this->subject('Confirmation de votre paiement de réservation')
                    ->markdown('emails.reservation-paid')
                    ->with([
                        'reservation' => $this->reservation,
                        'deliveryPrice' => $deliveryPrice,
                    ]);
    }
}