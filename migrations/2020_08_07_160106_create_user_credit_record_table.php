<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;
use Hyperf\DbConnection\Db;

class CreateUserCreditRecordTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_credit_record', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->index()->comment('用户ID');
            $table->string('type', 30)->comment('记录类型');
            $table->decimal('credit', 11, 2)->comment('分数变动');
            $table->string('remark', 255)->nullable()->comment('记录备注');
            $table->unsignedInteger('created_at')->comment('记录时间');
        });

        DB::statement('ALTER TABLE `user_credit_record` COMMENT = "用户信用分记录表";');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_credit_record');
    }
}
