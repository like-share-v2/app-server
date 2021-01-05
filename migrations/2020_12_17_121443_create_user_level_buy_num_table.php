<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateUserLevelBuyNumTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_level_buy_num', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->unsignedInteger('level')->comment('等级');
            $table->unsignedInteger('num')->comment('购买次数');
            $table->unsignedInteger('created_at')->comment('创建时间');
            $table->unsignedInteger('updated_at')->comment('更新时间');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_level_buy_num');
    }
}
