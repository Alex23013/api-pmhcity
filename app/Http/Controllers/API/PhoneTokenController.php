<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\PhoneToken;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PhoneTokenController extends BaseController
{
    // Generate a token for a phone number
    public function generateToken(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string|max:12', # +33 and 9 digits
        ]);

        // Generate a 6-digit token
        $token = Str::random(6);
        $expiresAt = Carbon::now()->addMinutes(15);

        // Store token
        PhoneToken::updateOrCreate(
            ['phone_number' => $request->phone_number],
            ['token' => $token, 'expires_at' => $expiresAt, 'is_verified' => false]
        );

        return response()->json([
            'status' => true,
            'message' => 'Token generated successfully.',
            'token' => $token, // In real scenarios, send via SMS instead
        ], 200);
    }


    public function verifyToken(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string|max:12',
            'token' => 'required|string|max:6',
        ]);

        $phoneToken = PhoneToken::where('phone_number', $request->phone_number)
            ->where('token', $request->token)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$phoneToken) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid or expired token.',
            ], 400);
        }

        // Mark as verified
        $phoneToken->update(['is_verified' => true]);

        return response()->json([
            'status' => true,
            'message' => 'Phone number verified successfully.',
        ], 200);
    }
}
