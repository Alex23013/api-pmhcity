<?php

namespace App\Http\Controllers;
use Stripe\Stripe;
use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function createPaymentIntent(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // Create a PaymentIntent
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $request->input('amount'),
                'currency' => $request->input('currency', 'eur'),
                'payment_method_types' => ['card'],
            ]);

            // Return the client_secret in the response
            return response()->json([
                'status' => true,
                'message' => 'PaymentIntent created successfully.',
                'client_secret' => $paymentIntent->client_secret,
            ], 200);
        } catch (\Exception $e) {
            // Handle errors
            return response()->json([
                'status' => false,
                'message' => 'Error creating PaymentIntent: ' . $e->getMessage(),
            ], 500);
        }
    }
}
