<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\Cause;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // 0. Overall Totals (Restored)
        $totalDonationsCents = Donation::where('status', 'paid')->sum('amount');
        $totalDonations = $totalDonationsCents / 100;

        $totalDonors = Donation::where('status', 'paid')->distinct('user_id')->count('user_id');

        // 1. Donations Count (Monthly & Yearly)
        $donationsCountMonthly = Donation::where('status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $donationsCountYearly = Donation::where('status', 'paid')
            ->whereYear('created_at', now()->year)
            ->count();

        // 2. Active Donors Count (Monthly & Yearly) - Unique Users
        $donorsCountMonthly = Donation::where('status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->distinct('user_id')
            ->count('user_id');

        $donorsCountYearly = Donation::where('status', 'paid')
            ->whereYear('created_at', now()->year)
            ->distinct('user_id')
            ->count('user_id');

        // 3. Completed Causes (Raised >= Target)
        $completedCauses = Cause::whereRaw('raised >= target')->count();

        // 4. Goal (Sum of all targets)
        $goal = Cause::sum('target');

        // 5. Collected (Sum of all raised amounts)
        $collected = Cause::sum('raised');

        // 6. Remaining (Goal - Collected)
        $remaining = $goal - $collected;
        // Ensure remaining is not negative
        $remaining = $remaining > 0 ? $remaining : 0;

        return response()->json([
            'status' => true,
            'message' => 'Dashboard stats fetched successfully',
            'data' => [
                'total_donations' => number_format($totalDonations, 2, '.', ''), // Overall Sum ($)
                'total_donors' => $totalDonors, // Overall Unique Users
                'donations_count_monthly' => $donationsCountMonthly,
                'donations_count_yearly' => $donationsCountYearly,
                'donors_count_monthly' => $donorsCountMonthly,
                'donors_count_yearly' => $donorsCountYearly,
                'completed_causes' => $completedCauses,
                'goal' => number_format($goal, 2, '.', ''),
                'collected' => number_format($collected, 2, '.', ''),
                'remaining' => number_format($remaining, 2, '.', ''),
            ]
        ]);
    }
}
