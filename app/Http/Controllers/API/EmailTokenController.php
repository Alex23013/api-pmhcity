<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\PhoneToken;
use Carbon\Carbon;
use App\Models\User;
use App\Mail\EmailVerificationTokenMail;
use Illuminate\Support\Facades\Mail;
use App\Models\EmailToken;
use Illuminate\Support\Facades\Validator;


class EmailTokenController extends BaseController
{
    public function generateToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|unique:users|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Email invalide',
                'errors' => $validator->errors(),
            ], 422);
        }
    
        $email = $request->email;
    
        // Generate 6-digit numeric token
        $token = rand(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(15);
    
        EmailToken::updateOrCreate(
            ['email' => $email],
            [
                'token' => $token,
                'expires_at' => $expiresAt,
                'is_verified' => false,
            ]
        );
    
        Mail::to($email)->send(new EmailVerificationTokenMail($email, $token));
    
        return response()->json([
            'success' => true,
            'message' => 'Code de vérification envoyé à votre e-mail.',
        ]);       
    }


    public function verifyToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|exists:email_tokens,email',
            'token' => 'required|digits:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Les données fournies ne sont pas valides.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $emailToken = EmailToken::where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$emailToken) {
            return response()->json([
                'success' => false,
                'message' => 'Jeton ou email invalide.',
            ], 400);
        }

        if (Carbon::now()->greaterThan($emailToken->expires_at)) {
            return response()->json([
                'success' => false,
                'message' => 'Le jeton a expiré.',
            ], 400);
        }

        // Mark as verified
        $emailToken->is_verified = true;
        $emailToken->save();

        return response()->json([
            'success' => true,
            'message' => 'Email vérifié avec succès.',
        ]);
    }

}
