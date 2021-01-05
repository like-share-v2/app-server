<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;
use Hyperf\DbConnection\Db;

class CreateUserInfoTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_info', function (Blueprint $table) {
            $table->integer('user_id')->unique()->comment('用户ID');
            $table->string('id_card', 30)->nullable()->unique()->comment('身份证号码');
            $table->string('bank_name', 50)->nullable()->comment('银行名');
            $table->string('name', 50)->nullable()->comment('姓名');
            $table->string('account', 20)->nullable()->comment('银行账号');
        });

        DB::statement('ALTER TABLE `user_info` COMMENT = "用户信息表";');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_info');
    }
}
