<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Support\Facades\Validator;
use App\Services\TransactionService;
use App\Services\ReservationService;
use App\Services\DeliveryService;


class TransactionController extends Controller
{

    protected $transactionService;
    protected $reservationService;
    protected $deliveryService;

    public function __construct(TransactionService $transactionService, ReservationService $reservationService, DeliveryService $deliveryService)
    {
        $this->transactionService = $transactionService;
        $this->reservationService = $reservationService;
        $this->deliveryService = $deliveryService;
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

        $deliveryData = [
            'reservation_id' => $request->reservation_id,
            'recipient' => $request->delivery_name,
            'address' => $request->delivery_address,
            'contact_phone' => $request->delivery_phone,
            'delivery_code' => strtoupper(bin2hex(random_bytes(3))), // Generate a random 6-character code
        ];

        $reservation = Reservation::find($request->reservation_id);
        $lastStep = $reservation->reservationSteps()->latest()->first();
        if (!$lastStep || $lastStep->reservationStatus->name !== 'accepted') {
            return response()->json([
                'status' => false,
                'message' => 'The reservation status can only be updated from "accepted".',
            ], 400);
        }
        
        $this->transactionService->createEarning($reservation);
        $this->reservationService->markAsPaid($reservation);
        $this->reservationService->markAsInTransit($reservation);
        $this->deliveryService->createDelivery($reservation, $deliveryData);
        
        return response()->json([
            'status' => true,
            'message' => 'Reservation paid',
        ], 200);
        
    } 
}
