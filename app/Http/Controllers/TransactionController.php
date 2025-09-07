<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\ReservationStatus;
use Illuminate\Support\Facades\Validator;
use App\Services\TransactionService;


class TransactionController extends Controller
{

    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function payReservation(Request $request){
        $validator = Validator::make($request->all(), [
            'reservation_id' => 'required|integer|exists:reservations,id',
            'email' => 'required|string|email|max:255',
            // TODO: add to reservations table as delivery or create a table delivery
            'delivery_name' => 'required',
            'delivery_phone' => 'required',
            'delivery_address' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $reservation = Reservation::find($request->reservation_id);
        $this->transactionService->createEarning($reservation);
        $this->markAsPaid($reservation);
        return response()->json([
            'status' => true,
            'message' => 'Reservation paid',
        ], 200);
        
    }

    protected function markAsPaid (Reservation $reservation){
        $newStatus = ReservationStatus::where('name', 'paid')->first();

        // Get the last step of this reservation
        $lastStep = $reservation->reservationSteps()->latest()->first();

        // Ensure the last status is 'accepted' before updating
        if (!$lastStep || $lastStep->reservationStatus->name !== 'accepted') {
            return response()->json([
                'status' => false,
                'message' => 'The reservation status can only be updated from "accepted".',
            ], 400);
        }

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
