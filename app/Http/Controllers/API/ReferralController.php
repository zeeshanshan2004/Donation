<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Referral;

class ReferralController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $perReferralRate = 3.00;

        $referrals = Referral::with(['referredUser'])
            ->where('referrer_id', $user->id)
            ->latest()
            ->get();

        $totalEarned = $referrals->count() * $perReferralRate;

        $history = $referrals->map(function ($ref) {
            return [
                'username'   => optional($ref->referredUser)->name ?? 'Unknown',
                'date'       => $ref->created_at->format('M d, Y'),
                'commission' => '+$' . number_format(3.00, 2),
            ];
        });

        return response()->json([
            'status'  => true,
            'message' => 'Referral data fetched successfully',
            'data'    => [
                'referral_code'    => $user->referral_code ?? null,
                'total_earned'     => '$' . number_format($totalEarned, 2),
                'per_referral'     => '$' . number_format($perReferralRate, 2),
                'total_count'      => $referrals->count(),
                'referral_history' => $history,
            ]
        ]);
    }
}
