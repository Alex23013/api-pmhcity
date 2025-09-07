<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Reservation;
use App\Models\Transaction;

class WalletController extends Controller
{
    public function portfolio()
    {
        $userId = Auth::user()->id;
        $user = User::find($userId);

        if (!in_array($user->role->id, [2, 3])) {
            return response()->json(['error' => 'User is not seller'], 404);
        }

        $reservations = Reservation::where('seller_id', $user->id)
                ->where('last_status', 'delivered')
                ->with(['seller', 'product'])
                ->latest()
                ->get();
        
        $reservationsHistoricInfo = $reservations->map(function ($reservation) {
            $transaction = Transaction::where('reference_type', Reservation::class)
                ->where('reference_id', $reservation->id)
                ->where('type', 'earning')
                ->first();
            
            return [
                'reservation_id' => $reservation->id,
                'product_name' => $reservation->product->name,
                'amount' => $reservation->price,
                'profit' => $transaction ? $transaction->amount : 0,
            ];
        });

        return response()->json([
            'user_id' => $user->id,
            'total_earnings' => $user->balance(),
            'delivered_reservations' => $reservationsHistoricInfo
        ]);
    }
}
