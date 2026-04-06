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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('cause_id')->constrained('causes')->onDelete('cascade');
            $table->string('stripe_payment_intent_id')->unique();
            $table->integer('amount'); // in cents
            $table->string('currency')->default('usd');
            $table->string('status')->default('pending'); // pending, paid, failed (enum handled as string for flexibility)
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
