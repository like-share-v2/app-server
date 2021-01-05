<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateWithdrawalRule1Table extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('withdrawal_rule_1', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 50)->comment('规则名称');
            $table->unsignedInteger('active_sub')->comment('有效下级');
            $table->unsignedInteger('withdrawal_count')->comment('可提现多少次');
            $table->unsignedTinyInteger('is_enable')->comment('是否启用');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawal_rule_1');
    }
}
