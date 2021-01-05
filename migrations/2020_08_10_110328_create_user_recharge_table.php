<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;
use Hyperf\DbConnection\Db;

class CreateUserRechargeTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_recharge', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->index()->comment('用户ID');
            $table->unsignedInteger('level')->index()->comment('充值等级');
            $table->unsignedDecimal('balance', 11, 2)->comment('充值金额');
            $table->unsignedInteger('payment_id')->index()->comment('支付订单ID');
            $table->unsignedInteger('recharge_time')->comment('充值时间');
            $table->unsignedInteger('channel')->index()->comment('充值渠道 1=在线充值 2=后台充值 3=手动充值');
            $table->unsignedInteger('admin_id')->nullable()->index()->comment('后台管理员ID');
            $table->string('remark', 255)->nullable()->comment('备注');
            $table->unsignedInteger('status')->default(0)->index()->comment('支付状态 0=未支付 1=已支付');
            $table->unsignedInteger('updated_at')->comment('更新时间');
        });

        Schema::create('payment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->index()->comment('用户ID');
            $table->string('pay_no', 50)->index()->comment('支付订单号');
            $table->unsignedDecimal('amount', 11, 2)->comment('支付金额');
            $table->unsignedInteger('type')->index()->comment('类型 1=充值');
            $table->string('channel', 50)->comment('支付渠道编号');
            $table->string('trade_no', 255)->nullable()->comment('第三方交易流水号');
            $table->unsignedInteger('status')->comment('支付状态 0=待支付;1=处理中;2=成功;3=失败;4=已退款');
            $table->string('result_desc')->nullable()->comment('第三方接口描述');
            $table->unsignedInteger('created_at')->comment('创建时间');
            $table->unsignedInteger('updated_at')->comment('更新时间');
        });

        Db::statement('ALTER TABLE `user_recharge` COMMENT = "用户充值表";');
        Db::statement('ALTER TABLE `payment` COMMENT = "支付表";');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_recharge');
        Schema::dropIfExists('payment');
    }
}
