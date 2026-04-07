<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\PackageOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;use Illuminate\Support\Str;
use App\Models\Referral;

class PackageOrderController extends Controller
{
    /**
     * Subscribe to a package (Static Payment Mock)
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'package_id'     => 'required|exists:packages,id',
            'transaction_id' => 'nullable|string',
        ]);

        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $package = Package::findOrFail($request->package_id);

        try {
            // 1. Create the order
            $newOrder = PackageOrder::create([
                'user_id'        => $user->id,
                'package_id'     => $package->id,
                'amount'         => $package->amount,
                'progress'       => 0,
                'status'         => 'active',
                'transaction_id' => $request->transaction_id,
            ]);

            // 2. Referral bonus — agar user ne referral_code se register kiya tha
            if ($user->referred_by) {
                Referral::firstOrCreate(
                    [
                        'referrer_id'      => $user->referred_by,
                        'referred_user_id' => $user->id,
                    ],
                    ['commission_earned' => 3.00]
                );
            }

            // 3. FIFO Logic
            $oldestActiveOrder = PackageOrder::where('package_id', $package->id)
                ->where('status', 'active')
                ->where('id', '!=', $newOrder->id)
                ->orderBy('created_at', 'ASC')
                ->first();

            if ($oldestActiveOrder) {
                $oldestActiveOrder->increment('progress');

                if ($oldestActiveOrder->progress >= $package->referral_required) {
                    $oldestActiveOrder->update([
                        'status'       => 'completed',
                        'completed_at' => now(),
                    ]);
                }
            }

            return response()->json([
                'status'  => true,
                'message' => 'Successfully subscribed to package!',
                'order'   => $newOrder
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Subscription failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's package orders
     */
    // public function myOrders()
    // {
    //     $user = Auth::guard('api')->user();
    //     if (!$user) {
    //         return response()->json(['error' => 'Unauthenticated'], 401);
    //     }

    //     $orders = PackageOrder::with('package')
    //         ->where('user_id', $user->id)
    //         ->orderBy('created_at', 'DESC')
    //         ->get();

    //     return response()->json([
    //         'status' => true,
    //         'orders' => $orders
    //     ]);
    // }
public function myOrders()
{
    $user = auth()->user(); // Ya Auth::guard('api')->user()

    if (!$user) {
        return response()->json(['error' => 'Unauthenticated'], 401);
    }

  $orders = PackageOrder::with('package')

            ->where('user_id', $user->id)

            ->orderBy('created_at', 'DESC')

            ->get();

    // Data ko transform karein taake har order ke sath fees calculate ho jaye
   $transformedOrders = $orders->map(function ($order) {
        $packageAmount = $order->package->amount; // Asli amount (e.g. 500)
        $expectedPayout = $packageAmount * 2;     // Double payout (e.g. 1000)
        
        // 1. Stripe Fee (2.9%)
        $stripeFee = ($expectedPayout * 2.9) / 100;
        
        // 2. Platform Tax (6%)
        $platformTax = ($expectedPayout * 6) / 100;
        
        // 3. Net Payout Calculation
        $netPayout = $expectedPayout - ($stripeFee + $platformTax);

        return [
            'id' => $order->id,
            // UI ke mutabiq "$500 Package" dikhane ke liye
            'package_header' => '$' . number_format($packageAmount, 0) . ' Package', 
            'package_amount' => number_format($packageAmount, 2),
            'status' => $order->status, 
            'purchased_date' => $order->created_at->format('M d, Y'),
            
            'calculations' => [
                'expected_payout' => number_format($expectedPayout, 2),
                'stripe_fee' => '-' . number_format($stripeFee, 2),
                'platform_tax' => '-' . number_format($platformTax, 2),
                'net_payout' => number_format($netPayout, 2),
            ]
        ];

    });
        return response()->json([
        'status' => true,
        'orders' => $transformedOrders
    ]);
}

    public function index(Request $request)
    {
        $user = $request->user();

        // 1. Total Earnings (Maan lo completed orders ka net payout)
        // Aap apne logic ke mutabiq isay change kar sakte hain
        $totalEarnings = PackageOrder::where('user_id', $user->id)
            ->where('status', 'completed')
            ->sum('amount') * 1.8; // Example logic 

        // 2. Referral Bonus ($3 per referral)
        $referralBonus = Referral::where('referrer_id', $user->id)->count() * 3;

        // 3. Total Donated (Jitni payment user ne ki)
        $totalDonated = PackageOrder::where('user_id', $user->id)->sum('amount');

        // 4. Active Packages Count
        $activePackages = PackageOrder::where('user_id', $user->id)
            ->where('status', 'active')
            ->count();

        return response()->json([
            'status' => true,
            'message' => 'Dashboard data fetched successfully',
            'data' => [
                'user_name' => $user->name, // Jo user login hai uska naam
                'current_date' => now()->format('l, F d, Y'), // e.g. Tuesday, March 17, 2026
                'stats' => [
                    'total_earnings' => '$' . number_format($totalEarnings, 0),
                    'referral_bonus' => '$' . number_format($referralBonus, 0),
                    'total_donated'  => '$' . number_format($totalDonated, 0),
                    'active_packages' => $activePackages
                ]
            ]
        ]);
    
}
}