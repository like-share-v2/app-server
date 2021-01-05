<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;
use Hyperf\DbConnection\Db;

class CreateUserNotifyTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_notify', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('type')->default(1)->index()->comment('类型 1=消息 2=新闻');
            $table->unsignedInteger('user_id')->default(0)->index()->comment('用户ID 为0等于全体用户消息');
            $table->string('title', 50)->comment('标题');
            $table->text('content')->comment('内容');
            $table->unsignedInteger('sort')->default(0)->comment('排序');
            $table->unsignedInteger('created_at')->comment('创建时间');
            $table->unsignedInteger('updated_at')->comment('更新时间');
        });

        Schema::create('user_read_record', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->index()->comment('用户ID');
            $table->unsignedInteger('notify_id')->index()->comment('关联消息或新闻ID');
        });

        DB::statement('ALTER TABLE `user_notify` COMMENT = "用户消息表";');

        Db::statement('ALTER TABLE `user_read_record` COMMENT = "用户已读记录表"');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_notify');

        Schema::dropIfExists('user_read_record');
    }
}
