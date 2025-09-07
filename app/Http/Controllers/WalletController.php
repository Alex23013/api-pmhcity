<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class WalletController extends Controller
{
    public function myEarnings()
    {
        $userId = Auth::user()->id;
        $user = User::find($userId);

        if (!in_array($user->role->id, [2, 3])) {
            return response()->json(['error' => 'User is not seller'], 404);
        }

        return response()->json([
            'user_id' => $user->id,
            'total_earnings' => $user->balance(),
        ]);
    }
}
