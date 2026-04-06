<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('causes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('heading')->nullable();
            $table->string('author')->nullable(); // by SMVM
            $table->string('image')->nullable();
            $table->decimal('target', 12, 2)->default(0);
            $table->decimal('raised', 12, 2)->default(0);
            $table->integer('days_left')->nullable();
            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('causes');
    }
};
