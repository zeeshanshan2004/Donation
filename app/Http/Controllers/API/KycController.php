<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\KycSubmission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class KycController extends Controller
{
    /**
     * Step 1: Submit KYC Details & Documents
     * POST /api/kyc/submit-details
     */
    public function submitDetails(Request $request)
    {
        try {
            $user = auth('api')->user();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized',
                ], 401);
            }

            // Get existing KYC submission (created during signup)
            $kycSubmission = KycSubmission::where('user_id', $user->id)->first();

            if (!$kycSubmission) {
                return response()->json([
                    'status' => false,
                    'message' => 'KYC submission not found. Please contact support.',
                ], 404);
            }

            // Check if already approved
            if ($kycSubmission->status === 'approved') {
                return response()->json([
                    'status' => false,
                    'message' => 'Your KYC is already approved.',
                ], 400);
            }

            // Validation
            $validator = Validator::make($request->all(), [
                'country_of_residence' => 'required|string|max:255',
                'full_legal_name' => 'required|string|max:255',
                'date_of_birth' => 'required|date|before:today',
                'residential_address' => 'required|string',
                'photo_id_type' => 'required|in:passport,drivers_license,greencard,visa',
                'photo_id' => 'required|image|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB max
                'face_photo' => 'required|image|mimes:jpg,jpeg,png|max:5120', // 5MB max
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Create directories if not exist
            $kycDir = storage_path('app/public/kyc');
            if (!file_exists($kycDir)) {
                mkdir($kycDir, 0755, true);
            }

            // Delete old files if they exist
            if ($kycSubmission->photo_id_path && \Storage::exists($kycSubmission->photo_id_path)) {
                \Storage::delete($kycSubmission->photo_id_path);
            }
            if ($kycSubmission->face_photo_path && \Storage::exists($kycSubmission->face_photo_path)) {
                \Storage::delete($kycSubmission->face_photo_path);
            }

            // Handle photo ID upload
            $photoIdPath = null;
            if ($request->hasFile('photo_id')) {
                $photoIdFile = $request->file('photo_id');
                $photoIdName = 'photo_id_' . $user->id . '_' . time() . '.' . $photoIdFile->getClientOriginalExtension();
                $photoIdPath = $request->file('photo_id')->storeAs('public/kyc', $photoIdName);
                $photoIdPath = str_replace('public/', '', $photoIdPath); // Remove 'public/' prefix for storage path
            }

            // Handle face photo upload
            $facePhotoPath = null;
            if ($request->hasFile('face_photo')) {
                $facePhotoFile = $request->file('face_photo');
                $facePhotoName = 'face_photo_' . $user->id . '_' . time() . '.' . $facePhotoFile->getClientOriginalExtension();
                $facePhotoPath = $request->file('face_photo')->storeAs('public/kyc', $facePhotoName);
                $facePhotoPath = str_replace('public/', '', $facePhotoPath); // Remove 'public/' prefix for storage path
            }

            // Update KYC submission
            $kycSubmission->update([
                'country_of_residence' => $request->country_of_residence,
                'full_legal_name' => $request->full_legal_name,
                'date_of_birth' => $request->date_of_birth,
                'residential_address' => $request->residential_address,
                'photo_id_type' => $request->photo_id_type,
                'photo_id_path' => $photoIdPath,
                'face_photo_path' => $facePhotoPath,
                'status' => 'pending',
                'is_agreement_confirmed' => false, // Reset if they are re-submitting
            ]);

            $kycSubmission->refresh();

            return response()->json([
                'status' => true,
                'message' => 'KYC Step 1 complete. Please download, sign, and email the agreement, then confirm in the app.',
                'data' => $kycSubmission,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to submit KYC',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Step 2: Confirm Agreement Sent via Email
     * POST /api/kyc/confirm-agreement
     */
    public function confirmAgreement(Request $request)
    {
        try {
            $user = auth('api')->user();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized',
                ], 401);
            }

            $kycSubmission = KycSubmission::where('user_id', $user->id)->first();

            if (!$kycSubmission) {
                return response()->json([
                    'status' => false,
                    'message' => 'KYC submission not found.',
                ], 404);
            }

            // Check if Step 1 is completed by verifying required fields
            $isStep1Complete = $kycSubmission->country_of_residence &&
                $kycSubmission->full_legal_name &&
                $kycSubmission->date_of_birth &&
                $kycSubmission->residential_address &&
                $kycSubmission->photo_id_path &&
                $kycSubmission->face_photo_path;

            if (!$isStep1Complete) {
                return response()->json([
                    'status' => false,
                    'message' => 'Please complete Step 1 (submit details and photos) before confirming the agreement.',
                ], 422);
            }

            // If already approved or already confirmed
            if ($kycSubmission->status === 'approved') {
                return response()->json(['status' => false, 'message' => 'Your KYC is already approved.'], 400);
            }

            // Validation for agreement file
            $validator = Validator::make($request->all(), [
                'agreement_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Create directories if not exist
            $kycDir = storage_path('app/public/kyc');
            if (!file_exists($kycDir)) {
                mkdir($kycDir, 0755, true);
            }

            // Delete old file if it exists
            if ($kycSubmission->agreement_path && \Storage::exists($kycSubmission->agreement_path)) {
                \Storage::delete($kycSubmission->agreement_path);
            }

            // Handle agreement file upload
            $agreementPath = null;
            if ($request->hasFile('agreement_file')) {
                $agreementFile = $request->file('agreement_file');
                $agreementName = 'agreement_' . $user->id . '_' . time() . '.' . $agreementFile->getClientOriginalExtension();
                $agreementPath = $request->file('agreement_file')->storeAs('public/kyc', $agreementName);
                $agreementPath = str_replace('public/', '', $agreementPath); // Remove 'public/' prefix
            }

            // Update confirmation status and path
            $kycSubmission->update([
                'agreement_path' => $agreementPath,
                'is_agreement_confirmed' => true,
                'status' => 'pending', // Ensure it stays pending for Admin review
            ]);

            $kycSubmission->refresh();

            return response()->json([
                'status' => true,
                'message' => 'Agreement successfully uploaded. Your KYC is now under review by our team.',
                'data' => $kycSubmission,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to confirm agreement',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get KYC status for current user
     * GET /api/kyc/status
     */
    public function status()
    {
        try {
            $user = auth('api')->user();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized',
                ], 401);
            }

            $kycSubmission = KycSubmission::where('user_id', $user->id)->first();

            return response()->json([
                'status' => true,
                'kyc_status' => $user->kyc_status,
                'kyc_submission' => $kycSubmission,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch KYC status',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
