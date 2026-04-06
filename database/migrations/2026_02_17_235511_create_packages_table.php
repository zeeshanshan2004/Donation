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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon')->nullable(); // Store icon path (e.g. icons/leaf.png)
            $table->decimal('amount', 15, 2);
            $table->integer('referral_required');
            $table->decimal('tax_percentage', 5, 2)->default(0.00); // For calculations
            $table->decimal('community_share', 15, 2);
            $table->boolean('is_popular')->default(false); // For that "Silver" gold border effect
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};