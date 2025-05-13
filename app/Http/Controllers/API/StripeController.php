<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function createCheckoutSession(Request $request)
    {
        // Set your Stripe secret key
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // Create a Checkout Session
            $session = Session::create([
                'payment_method_types' => ['card'], // Specify payment methods
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $request->input('product_name', 'Default Product'),
                        ],
                        'unit_amount' => $request->input('amount', 1000), // Amount in cents
                    ],
                    'quantity' => $request->input('quantity', 1),
                ]],
                'mode' => 'payment', // Payment mode
                'ui_mode' => 'embedded',
                'redirect_on_completion' => 'never',
            ]);

            // Return the client_secret in the response
            return response()->json([
                'status' => true,
                'message' => 'Checkout Session created successfully.',
                'client_secret' => $session->client_secret,
            ], 200);
        } catch (\Exception $e) {
            // Handle errors
            return response()->json([
                'status' => false,
                'message' => 'Error creating Checkout Session: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function createPaymentIntent(Request $request)
    {
        // Set your Stripe secret key
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

    //TODO: create a mark as completed endpoint, que deberia ir en el return link del stripe

    //TODO: use the checkPaymentStatus inside the mark as completed endpoint
    public function checkPaymentStatus(Request $request)
    {
        // Set your Stripe secret key
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // Retrieve the PaymentIntent
            $paymentIntent = \Stripe\PaymentIntent::retrieve($request->input('payment_intent_id'));

            // Return the status of the PaymentIntent
            return response()->json([
                'status' => true,
                'message' => 'Payment status retrieved successfully.',
                'payment_status' => $paymentIntent->status,
            ], 200);
        } catch (\Exception $e) {
            // Handle errors
            return response()->json([
                'status' => false,
                'message' => 'Error retrieving PaymentIntent: ' . $e->getMessage(),
            ], 500);
        }
    }
}
