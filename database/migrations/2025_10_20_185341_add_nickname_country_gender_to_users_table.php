<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('nickname')->nullable()->after('name');
        $table->string('country')->nullable()->after('nickname');
        $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('country');
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['nickname', 'country', 'gender']);
    });
}

};
