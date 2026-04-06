<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KycSubmission;
use App\Models\User;
use Illuminate\Http\Request;

class KycController extends Controller
{
    /**
     * Display pending KYC submissions
     * GET /admin/kyc
     */
    public function index()
    {
        $submissions = KycSubmission::with(['user', 'reviewer'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.kyc.index', compact('submissions'));
    }

    /**
     * Show detailed KYC submission
     * GET /admin/kyc/{id}
     */
    public function show($id)
    {
        $submission = KycSubmission::with(['user', 'reviewer'])->findOrFail($id);
        return view('admin.kyc.show', compact('submission'));
    }

    /**
     * Approve KYC submission
     * POST /admin/kyc/{id}/approve
     */
    public function approve($id)
    {
        try {
            $submission = KycSubmission::findOrFail($id);

            // Update submission status
            $submission->status = 'approved';
            $submission->reviewed_by = auth()->guard('web')->id();
            $submission->reviewed_at = now();
            $submission->rejection_reason = null;
            $submission->save();

            // Update user's KYC status to true (approved)
            $user = User::find($submission->user_id);
            if ($user) {
                $user->kyc_status = true; // ✅ Boolean true
                $user->save();
            }

            return redirect()->route('admin.kyc.index')
                ->with('success', 'KYC approved successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to approve KYC: ' . $e->getMessage());
        }
    }

    /**
     * Reject KYC submission
     * POST /admin/kyc/{id}/reject
     */
    public function reject(Request $request, $id)
    {
        try {
            $request->validate([
                'rejection_reason' => 'required|string|min:10',
            ]);

            $submission = KycSubmission::findOrFail($id);

            // Update submission status
            $submission->status = 'rejected';
            $submission->reviewed_by = auth()->guard('web')->id();
            $submission->reviewed_at = now();
            $submission->rejection_reason = $request->rejection_reason;
            $submission->save();

            // Update user's KYC status to false (rejected)
            $user = User::find($submission->user_id);
            if ($user) {
                $user->kyc_status = false; // ✅ Boolean false
                $user->save();
            }

            return redirect()->route('admin.kyc.index')
                ->with('success', 'KYC rejected successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to reject KYC: ' . $e->getMessage());
        }
    }
}
