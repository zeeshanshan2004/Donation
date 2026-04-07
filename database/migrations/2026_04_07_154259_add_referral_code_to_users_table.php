<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        // Ye line aapne add karni hai
        $table->string('referral_code')->unique()->nullable()->after('email');
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        // Rollback ke liye column remove karne ka code
        $table->dropColumn('referral_code');
    });
}
};
