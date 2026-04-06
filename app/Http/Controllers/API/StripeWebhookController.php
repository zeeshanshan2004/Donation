<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Cause;
use Illuminate\Http\Request;
use Stripe\Webhook;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object; // contains a StripePaymentIntent
                $this->handlePaymentSuccess($paymentIntent);
                break;
            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object;
                $this->handlePaymentFailure($paymentIntent);
                break;
            default:
            // Unexpected event type
            // echo 'Received unknown event type ' . $event->type;
        }

        return response()->json(['status' => 'success']);
    }

    protected function handlePaymentSuccess($paymentIntent)
    {
        $donation = Donation::where('stripe_payment_intent_id', $paymentIntent->id)->first();

        if ($donation && $donation->status !== 'paid') {
            $donation->status = 'paid';
            $donation->paid_at = now();
            $donation->save();

            // Increment Cause Raised Amount
            // Amount is in cents, convert to dollars/main unit if 'raised' is stored as float/decimal
            // Assuming 'raised' column in causes table is decimal/double.
            $cause = $donation->cause;
            if ($cause) {
                $amountInDollars = $donation->amount / 100;
                $cause->increment('raised', $amountInDollars);
            }

            Log::info("Donation {$donation->id} marked as paid.");
        }
    }

    protected function handlePaymentFailure($paymentIntent)
    {
        $donation = Donation::where('stripe_payment_intent_id', $paymentIntent->id)->first();

        if ($donation) {
            $donation->status = 'failed';
            $donation->save();
            Log::info("Donation {$donation->id} marked as failed.");
        }
    }
}
