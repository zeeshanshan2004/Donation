<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Referral; // Referral model import karein
use App\Models\User;

class ReferralController extends Controller
{
    public function index(Request $request)
    {
        
        // 1. Current login user pakrein
        $user = $request->user(); 
        
        // 2. Default rate jo aapne $3 manga tha
        $perReferralRate = 3.00;

        // 3. Is user ne jin logon ko refer kiya unki list uthaein
        // Hum 'referredUser' relationship load kar rahe hain taake naam aur package mil sakay
        $referrals = Referral::with(['referredUser.package'])
            ->where('referrer_id', $user->id)
            ->latest()
            ->get();

        // 4. Total kamayi calculate karein (Count * $3)
        $totalEarned = $referrals->count() * $perReferralRate;

        // 5. History ko format karein jaisa UI mein chahiye
        $history = $referrals->map(function($ref) {
            return [
                'username' => $ref->referredUser->name ?? 'Unknown',
                'date' => $ref->created_at->format('M d, Y'),
                // Agar user ka koi package hai to wo dikhaein
                'package' => $ref->referredUser->package->name ?? 'Silver package', 
                'commission' => '+$' . number_format(3.00, 2)
            ];
        });

        // 6. Final JSON Response
        return response()->json([
            'status' => true,
            'message' => 'Referral data fetched successfully',
            'data' => [
                'referral_code' => $user->referral_code, // Jo random code humne banaya
                'total_earned' => '$' . number_format($totalEarned, 2),
                'per_referral' => '$' . number_format($perReferralRate, 2),
                'total_count' => $referrals->count() . ' total',
                'referral_history' => $history
            ]
        ]);
    }
    
}