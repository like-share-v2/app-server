<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;
use Hyperf\DbConnection\Db;

class CreateUserWithdrawalTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_withdrawal', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->index()->comment('用户ID');
            $table->unsignedDecimal('amount', 11, 2)->comment('提现金额');
            $table->string('bank_name', 50)->nullable()->comment('银行名');
            $table->string('name', 50)->nullable()->comment('姓名');
            $table->string('account', 20)->nullable()->comment('银行账号');
            $table->unsignedInteger('status')->index()->comment('提现状态 0=待审核 1=已打款 2=已拒绝');
            $table->unsignedInteger('admin_id')->nullable()->index()->comment('系统管理员ID');
            $table->string('remark', 255)->nullable()->comment('审核备注');
            $table->unsignedInteger('created_at')->comment('创建时间');
            $table->unsignedInteger('updated_at')->comment('更新时间');
        });

        Db::statement('ALTER TABLE `user_withdrawal` COMMENT = "用户提现表";');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_withdrawal');
    }
}
