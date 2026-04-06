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
    Schema::table('causes', function (Blueprint $table) {
        $table->boolean('is_featured')->default(0)->after('days_left');
    });
}

public function down()
{
    Schema::table('causes', function (Blueprint $table) {
        $table->dropColumn('is_featured');
    });
}

};
