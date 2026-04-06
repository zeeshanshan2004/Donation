<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // 🔹 Basic User Info
            $table->string('name');
            $table->string('email')->unique();

            // 🔹 Password for login
            $table->string('password');

            // 🔹 OTP + Verification
            $table->string('otp', 4)->nullable();
            $table->boolean('is_verified')->default(false);

            // 🔹 Optional: useful in JWT for token invalidation after password reset
            $table->rememberToken();

            // 🔹 Default Laravel timestamps (created_at, updated_at)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
