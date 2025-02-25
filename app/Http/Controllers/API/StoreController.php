<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Store;

class StoreController extends BaseController
{
    public function verifyStore(Request $request)
    {
        $request->validate([
            'siret' => 'required|string',
            'company_name' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $store = $user->store; // Ensure the user has a store

        if (!$store) {
            return response()->json([
                'status' => false,
                'message' => 'Store not found for this user.'
            ], 404);
        }

        // Update store details
        $store->update([
            'siret' => $request->siret,
            'name' => $request->company_name,
        ]);

        $user = User::find($user->id); 
        $user->update([
            'profile_status' => 'pending revision'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Store data sent successfully, profile under review.',
            'data' => [
                'store' => $store,
                'user' => $user
            ]
        ], 200);
    }

    public function markAsVerifiedStore(Request $request, $storeId)
    {
        $store = Store::find($storeId);

        if (!$store) {
            return response()->json([
                'status' => false,
                'message' => 'Store not found.'
            ], 404);
        }

        $store->update(['is_verified' => true]);

        return response()->json([
            'status' => true,
            'message' => 'Store verified successfully.',
            'data' => $store
        ], 200);
    }
}
