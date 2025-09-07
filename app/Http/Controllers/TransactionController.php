<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
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

        return response()->json([
            'status' => true,
            'message' => 'Reservation paid',
        ], 200);
        
    }

    protected function createEarning(Reservation $reservation){
        $seller = User::find($reservation->seller_id);
        Transaction::factory()->earning(100)->create(['user_id' => $seller->id]);
    }
}
