<?php
namespace App\Services;

use App\Models\Reservation;
use App\Models\ReservationStatus;

class ReservationService
{
    public function markAsPaid (Reservation $reservation){
        $lastStep = $reservation->reservationSteps()->latest()->first();
        if (!$lastStep || $lastStep->reservationStatus->name !== 'accepted') {
            return response()->json([
                'status' => false,
                'message' => 'The reservation status can only be updated from "accepted".',
            ], 400);
        }
        $this->setNewStatus($reservation, 'paid');
    }

    public function markAsInTransit(Reservation $reservation){
        $lastStep = $reservation->reservationSteps()->latest()->first();
        if (!$lastStep || $lastStep->reservationStatus->name !== 'paid') {
            return response()->json([
                'status' => false,
                'message' => 'The reservation status can only be updated from "paid".',
            ], 400);
        }
        $this->setNewStatus($reservation, 'in_transit');
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