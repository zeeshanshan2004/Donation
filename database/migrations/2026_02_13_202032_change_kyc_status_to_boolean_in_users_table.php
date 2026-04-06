<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Add a temporary column
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('kyc_status_temp')->default(false)->after('kyc_status');
        });

        // Step 2: Migrate data from old enum to new boolean
        DB::statement("UPDATE users SET kyc_status_temp = IF(kyc_status = 'approved', 1, 0)");

        // Step 3: Drop the old enum column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('kyc_status');
        });

        // Step 4: Rename temp column to kyc_status
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('kyc_status_temp', 'kyc_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add temp enum column
        Schema::table('users', function (Blueprint $table) {
            $table->enum('kyc_status_temp', ['not_submitted', 'pending', 'approved', 'rejected'])
                ->default('not_submitted')
                ->after('kyc_status');
        });

        // Migrate data back
        DB::statement("UPDATE users SET kyc_status_temp = IF(kyc_status = 1, 'approved', 'not_submitted')");

        // Drop boolean column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('kyc_status');
        });

        // Rename back
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('kyc_status_temp', 'kyc_status');
        });
    }
};
