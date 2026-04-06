<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\Request;

class AdminDonationController extends Controller
{
    public function index(Request $request)
    {
        $donations = Donation::with(['user', 'cause'])
            ->latest()
            ->paginate(20);

        return response()->json([
            'status' => true,
            'message' => 'All donations fetched successfully',
            'data' => $donations
        ]);
    }
}
