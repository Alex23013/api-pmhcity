<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Models\ReservationStep;
use App\Models\ReservationStatus;
use App\Models\Product;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * List all reservations of the logged-in user (as a buyer or seller)
     */
    public function index()
    {
        $user = Auth::user();
        $auth_role = $user->role_id;
        if($auth_role == 4) {
            $reservations = Reservation::where('buyer_id', $user->id)
                ->with(['buyer', 'seller'])
                ->latest()
                ->get();
        } else { // role 2 or 3 (sellers)
            $reservations = Reservation::where('seller_id', $user->id)
                ->with(['buyer', 'seller'])
                ->latest()
                ->get();
        }

        return response()->json([
            'status' => true,
            'message' => 'Auth reservations retrieved successfully',
            'data' => [
                "auth_role" => $user->role->name,
                "reservations" => $reservations
            ],
        ], 200);
    }

    /**
     * Show reservation steps for a specific reservation
     */
    public function showSteps($id)
    {
        $reservation = Reservation::with('reservationSteps')->find($id);

        if (!$reservation) {
            return response()->json([
                'status' => false,
                'message' => 'Reservation not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Reservation details retrieved successfully',
            'data' => $reservation->reservationSteps
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $buyer = Auth::user();

        // Ensure the logged-in user is a buyer
        if ($buyer->role->id !== 4) {
            return response()->json([
                'status' => false,
                'message' => 'Only buyers can create reservations.'
            ], 403);
        }

        $product = Product::find($request->product_id);
        // Create reservation
        $reservation = Reservation::create([
            'buyer_id' => $buyer->id,
            'seller_id' => $product->user->id,
            'product_id' => $product->id,
            'quantity' => $request->quantity??1,
            'comment' => $request->comment??null,
            // TODO: add color depending on how frontend will handle it
            'last_status' => 'created', // Default status
        ]);

        ReservationStep::create([
            'reservation_id' => $reservation->id,
            'reservation_status_id' => 1, // Default status
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Reservation created successfully',
            'data' => $reservation->load('reservationSteps')
        ], 201);
    }

    public function updateStatus(Request $request)
    {
        // Validate the input
        $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'new_status' => 'required|in:2,3',
        ]);

        $user = Auth::user();
        $newStatus = ReservationStatus::find($request->new_status);
        // Check if the user has permission (role < 4)
        if ($user->role_id >= 4) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized. You do not have permission to update reservation status.',
            ], 403);
        }

        $reservation = Reservation::findOrFail($request->reservation_id);

        // Get the last step of this reservation
        $lastStep = $reservation->reservationSteps()->latest()->first();

        // Ensure the last status is 'created' before updating
        if (!$lastStep || $lastStep->reservationStatus->name !== 'created') {
            return response()->json([
                'status' => false,
                'message' => 'The reservation status can only be updated from "created".',
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

        return response()->json([
            'status' => true,
            'message' => "Reservation updated to {$newStatus->name}.",
            'data' => [
                'reservation' => $reservation,
            ],
        ], 200);
    }

}
