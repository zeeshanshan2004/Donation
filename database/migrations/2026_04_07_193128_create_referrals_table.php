<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::create('referrals', function (Blueprint $table) {
        $table->id();
        
        // Jo banda dashboard dekh raha hai (Referrer)
        $table->unsignedBigInteger('referrer_id'); 
        
        // Jis bande ne naya join kiya (Referred User)
        $table->unsignedBigInteger('referred_user_id'); 
        
        // Kitna commission mila (Default $3.00)
        $table->decimal('commission_earned', 8, 2)->default(3.00);
        
        $table->timestamps();

        // Foreign keys (Optional but recommended for data safety)
        $table->foreign('referrer_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('referred_user_id')->references('id')->on('users')->onDelete('cascade');
    });
}
};
