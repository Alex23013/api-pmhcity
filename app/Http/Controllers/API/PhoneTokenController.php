<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\PhoneToken;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Twilio\Rest\Client;

class PhoneTokenController extends BaseController
{
    // Generate a token for a phone number
    public function generateToken(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string|max:12', # +33 and 9 digits
        ]);

        // Generate a 6-digit token
        $phone_token = mt_rand(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(15);
        $message = null;

        // Ensure the phone number starts with +33 unless it starts with +51
        $phone_number = $request->phone_number;
        if (!str_starts_with($phone_number, '+51') && (strlen($phone_number) === 9 )) {
            $phone_number = '+33' . $phone_number;
        }

        if(getenv("TWILIO_MODE") == "prod"){
            try {
                $sid = getenv("TWILIO_ACCOUNT_SID");
                $token = getenv("TWILIO_AUTH_TOKEN");

                $twilio = new Client($sid, $token);

                $message = $twilio->messages
                    ->create($phone_number, // to
                        [
                            "from" => "+15309995988",
                            "body" => "Votre code de vérification PMHCity est $phone_token"
                        ]
                    );

            } catch (\Twilio\Exceptions\RestException $e) {
                // Handle Twilio-specific errors
                return response()->json([
                    'status' => false,
                    'message' => 'Error sending SMS: ' . $e->getMessage(),
                ], 400);
            } catch (\Exception $e) {
                // Handle general errors
                return response()->json([
                    'status' => false,
                    'message' => 'An unexpected error occurred: ' . $e->getMessage(),
                ], 500);
            }
        }
        
        if($message && $message->status == 'queued'){
            // Store token
            PhoneToken::updateOrCreate(
                ['phone_number' => $request->phone_number],
                ['token' => $phone_token, 'expires_at' => $expiresAt, 'is_verified' => false]
            );
            return response()->json([
                'status' => true,
                'message' => 'Token generated successfully.'
            ], 200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Error sending SMS.'
            ], 400);
        }       
    }


    public function verifyToken(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string|max:12',
            'token' => 'required|string|max:6',
        ]);

        if ((getenv("TWILIO_MODE") != "prod") && $request->token == '299613'){ //master dev token
            return response()->json([
                'status' => true,
                'message' => 'Phone number verified successfully.',
            ], 200);
        }else{
            $phone_token = PhoneToken::where('phone_number', $request->phone_number)
            ->where('token', $request->token)
            ->where('expires_at', '>', Carbon::now())
            ->first();
        }

        if (!$phone_token) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid or expired token.',
            ], 400);
        }
        // Mark as verified
        $phone_token->update(['is_verified' => true]);

        return response()->json([
            'status' => true,
            'message' => 'Phone number verified successfully.',
        ], 200);
    }

    public function verifyTokenInProfile(Request $request){
        $user = Auth::user();
        $request = request();
        $request->validate([
            'token' => 'required|string|max:6',
        ]);

        if ((getenv("TWILIO_MODE") != "prod") && $request->token == '299613'){ //master dev token
            $user = User::find($user->id); 
            $user->update([
                'phone' => $request->phone_number
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Phone number updated successfully.',
            ], 200);
        }else{
            $phone_token = PhoneToken::where('phone_number', $request->phone_number)
            ->where('token', $request->token)
            ->where('expires_at', '>', Carbon::now())
            ->first();
        }

        if (!$phone_token) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid or expired token.',
            ], 400);
        }
        // Mark as verified
        $phone_token->update(['is_verified' => true]);
        $user = User::find($user->id); 
        $user->update([
            'phone' => $request->phone_number
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Phone number verified successfully.',
        ], 200);
    }
}
