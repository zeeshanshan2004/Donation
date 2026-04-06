<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $submissions = DB::table('kyc_submissions')->get();

        // Ensure destination directory exists
        if (!Storage::disk('public')->exists('kyc')) {
            Storage::disk('public')->makeDirectory('kyc');
        }

        foreach ($submissions as $submission) {
            $updates = [];

            // Handle Photo ID
            if ($submission->photo_id_path && strpos($submission->photo_id_path, 'uploads/') === 0) {
                $oldPath = public_path($submission->photo_id_path);
                $filename = basename($submission->photo_id_path);
                $newPath = 'kyc/' . $filename; // Relative to storage/app/public

                if (File::exists($oldPath)) {
                    // Move file to storage
                    Storage::disk('public')->put($newPath, File::get($oldPath));
                    // Optional: Delete old file
                    // File::delete($oldPath); 

                    $updates['photo_id_path'] = $newPath;
                } else {
                    // If file doesn't exist but DB has path, just update path assuming user might re-upload or it's lost
                    // Better to keep it pointing to new structure or null? 
                    // Let's just update the string format to match new system, even if file missing
                    $updates['photo_id_path'] = $newPath;
                }
            }

            // Handle Face Photo
            if ($submission->face_photo_path && strpos($submission->face_photo_path, 'uploads/') === 0) {
                $oldPath = public_path($submission->face_photo_path);
                $filename = basename($submission->face_photo_path);
                $newPath = 'kyc/' . $filename;

                if (File::exists($oldPath)) {
                    Storage::disk('public')->put($newPath, File::get($oldPath));
                    $updates['face_photo_path'] = $newPath;
                } else {
                    $updates['face_photo_path'] = $newPath;
                }
            }

            if (!empty($updates)) {
                DB::table('kyc_submissions')
                    ->where('id', $submission->id)
                    ->update($updates);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverting file moves is complex and risky, mostly simply reverting DB paths
        // This is a fix-forward migration mainly
    }
};
