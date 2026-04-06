<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cause;
use App\Models\Donation;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class DonationController extends Controller
{
    public function createIntent(Request $request)
    {
        $request->validate([
            'cause_id' => 'required|exists:causes,id',
            'amount' => 'required|integer|min:50', // Minimum 50 cents
        ]);

        $cause = Cause::where('id', $request->cause_id)->where('status', true)->first();
        if (!$cause) {
            return response()->json(['message' => 'Cause not found or inactive'], 404);
        }

        try {
            // BYPASS STRIPE: Direct Manual Donation
            // Generate a dummy ID to satisfy the unique constraint
            $dummyPaymentId = 'manual_' . time() . '_' . rand(1000, 9999);

            // Store PAID donation immediately
            $donation = Donation::create([
                'user_id' => auth()->id(),
                'cause_id' => $cause->id,
                'transaction_id' => $request->transaction_id ?? null,
                'stripe_payment_intent_id' => $dummyPaymentId,
                'amount' => $request->amount,
                'currency' => 'usd',
                'status' => 'paid', // Directly Paid
                'paid_at' => now(),
            ]);

            // Increment Cause Raised Amount immediately
            $amountInDollars = $request->amount / 100;
            $cause->increment('raised', $amountInDollars);

            return response()->json([
                'status' => true,
                'message' => 'Donation successful (Manual Mode)',
                'data' => $donation
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function history(Request $request)
    {
        $donations = Donation::with('cause')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
        return response()->json([
            'status' => true,
            'message' => 'Donation history fetched',
            'data' => $donations
        ]);
    }

}
