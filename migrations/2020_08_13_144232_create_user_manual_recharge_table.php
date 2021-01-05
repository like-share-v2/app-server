<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;
use Hyperf\DbConnection\Db;

class CreateUserManualRechargeTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_manual_recharge', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->index()->comment('用户ID');
            $table->unsignedInteger('level')->index()->comment('充值等级');
            $table->unsignedDecimal('amount', 11, 2)->comment('充值金额');
            $table->string('trade_no', 255)->comment('第三方交易流水号');
            $table->string('image', 255)->comment('支付截图');
            $table->unsignedInteger('status')->default(0)->index()->comment('状态 0=待审核 1=已通过 2=已拒绝');
            $table->unsignedInteger('admin_id')->nullable()->index()->comment('审核管理员ID');
            $table->string('remark', 255)->nullable()->comment('审核备注');
            $table->unsignedInteger('created_at')->comment('创建时间');
            $table->unsignedInteger('updated_at')->comment('更新时间');
        });

        Db::statement('ALTER TABLE `user_manual_recharge` COMMENT = "用户手动充值表";');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_manual_recharge');
    }
}
