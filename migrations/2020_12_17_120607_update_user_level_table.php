<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class UpdateUserLevelTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_level', function (Blueprint $table) {
            $table->unsignedInteger('max_buy_num')->default(0)->comment('最大购买次数');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_level', function (Blueprint $table) {
            //
        });
    }
}
