<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;
use Hyperf\DbConnection\Db;

class CreateUserBillTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_bill', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->index()->comment('用户ID');
            $table->unsignedInteger('way')->index()->comment('账单类型 1=提现 2=任务 3=下级提成 4=系统');
            $table->string('type', 50)->comment('变动原因');
            $table->decimal('balance', 11, 2)->comment('账单金额');
            $table->unsignedDecimal('before_balance', 11, 2)->comment('变化前金额');
            $table->unsignedDecimal('after_balance', 11, 2)->comment('变化后金额');
            $table->string('remark', 255)->nullable()->comment('账单备注');
            $table->unsignedInteger('low_id')->default(0)->index()->comment('下级账单来源用户ID 0=无');
            $table->unsignedInteger('created_at')->comment('创建时间');
            $table->unsignedInteger('updated_at')->comment('更新时间');
        });

        Db::statement('ALTER TABLE `user_bill` COMMENT = "用户账单表";');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_bill');
    }
}
