<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->decimal('tax_percentage', 5, 2)->default(6.00)->after('referral_required');
            $table->dropColumn('total_collected');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('tax_percentage');
            $table->decimal('total_collected', 15, 2)->default(0.00)->after('referral_required');
        });
    }
};
