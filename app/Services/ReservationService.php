<?php
namespace App\Services;

use App\Models\Reservation;
use App\Models\ReservationStatus;

class ReservationService
{
    public function markAsAccepted(Reservation $reservation){
        $this->setNewStatus($reservation, 'accepted');
    }

    public function markAsDeclined(Reservation $reservation){
        $this->setNewStatus($reservation, 'declined');
    }

    public function markAsPaid (Reservation $reservation){
        $this->setNewStatus($reservation, 'paid');
    }

    public function markAsInTransit(Reservation $reservation){
        $this->setNewStatus($reservation, 'in_transit');
    }

    public function markAsDelivered(Reservation $reservation){
        $this->setNewStatus($reservation, 'delivered');
    }

    public function setNewStatus(Reservation $reservation, $statusName){
        $newStatus = ReservationStatus::where('name', $statusName)->first();

        // Create a new reservation step with the new status
        $reservationStep = $reservation->reservationSteps()->create([
            'reservation_status_id' => $newStatus->id,
        ]);

        // Update the last_status field in the reservation
        $reservation->update([
            'last_status' => $newStatus->name,
        ]);
    }
}