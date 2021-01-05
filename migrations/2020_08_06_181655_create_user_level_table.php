<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;
use Hyperf\DbConnection\Db;

class CreateUserLevelTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_level', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('level')->unique()->comment('会员等级');
            $table->string('name', 30)->comment('会员名称');
            $table->string('icon', 255)->comment('会员图标');
            $table->unsignedDecimal('price', 11, 2)->comment('会员价格');
            $table->unsignedInteger('task_num')->comment('每日任务数量');
            $table->unsignedInteger('created_at')->comment('注册时间');
            $table->unsignedInteger('updated_at')->comment('更新时间');
        });

        Schema::create('user_level_rebate', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('level_id')->index()->comment('等级ID');
            $table->unsignedTinyInteger('type')->index()->comment('奖励方式 1=充值奖励 2=完成任务奖励');
            $table->unsignedDecimal('p_one_rebate', 11, 2)->comment('上级奖励');
            $table->unsignedDecimal('p_two_rebate', 11, 2)->comment('二级奖励');
            $table->unsignedDecimal('p_three_rebate', 11, 2)->comment('三级奖励');
        });


        DB::statement('ALTER TABLE `user_level` COMMENT = "会员等级表";');
        DB::statement('ALTER TABLE `user_level_rebate` COMMENT = "会员等级奖励表";');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_level');
        Schema::dropIfExists('user_level_rebate');
    }
}
