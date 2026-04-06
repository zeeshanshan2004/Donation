<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\PackageOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class PackageOrderController extends Controller
{
    /**
     * Subscribe to a package (Static Payment Mock)
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
        ]);

        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $package = Package::findOrFail($request->package_id);

        try {
            // 1. Create the order for the current user
            $newOrder = PackageOrder::create([
                'user_id' => $user->id,
                'package_id' => $package->id,
                'amount' => $package->amount,
                'progress' => 0,
                'status' => 'active',
            ]);

            // 2. FIFO Logic: Find the FIRST (oldest) active order for this package
            // This order should not be the one we just created.
            $oldestActiveOrder = PackageOrder::where('package_id', $package->id)
                ->where('status', 'active')
                ->where('id', '!=', $newOrder->id)
                ->orderBy('created_at', 'ASC')
                ->first();

            if ($oldestActiveOrder) {
                // Increment progress of the oldest active member
                $oldestActiveOrder->increment('progress');

                // Check if they reached the required limit
                if ($oldestActiveOrder->progress >= $package->referral_required) {
                    $oldestActiveOrder->update([
                        'status' => 'completed',
                        'completed_at' => now(),
                    ]);
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'Successfully subscribed to package!',
                'order' => $newOrder
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
    public function myOrders()
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $orders = PackageOrder::with('package')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'DESC')
            ->get();

        return response()->json([
            'status' => true,
            'orders' => $orders
        ]);
    }
}
