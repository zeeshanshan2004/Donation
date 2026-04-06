<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('causes', function (Blueprint $table) {
            // Safely add columns only if they don't exist
            if (!Schema::hasColumn('causes', 'featured') && !Schema::hasColumn('causes', 'is_featured')) {
                $table->boolean('featured')->default(0)->after('days_left');
            }

            if (!Schema::hasColumn('causes', 'status')) {
                $table->boolean('status')->default(1)->after('featured');
            }

            if (!Schema::hasColumn('causes', 'description')) {
                $table->text('description')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('causes', function (Blueprint $table) {
            if (Schema::hasColumn('causes', 'featured')) {
                $table->dropColumn('featured');
            }
            if (Schema::hasColumn('causes', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('causes', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};
