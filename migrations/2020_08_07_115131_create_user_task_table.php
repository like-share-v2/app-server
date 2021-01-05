<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;
use Hyperf\DbConnection\Db;

class CreateUserTaskTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_task', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->index()->comment('用户ID');
            $table->unsignedInteger('task_id')->index()->comment('任务ID');
            $table->unsignedInteger('status')->index()->comment('状态 0=进行中 1=待审核 2=已完成 3=已拒绝 4=取消');
            $table->string('image', 255)->nullable()->comment('任务截图');
            $table->unsignedDecimal('amount', 11, 2)->comment('任务金额');
            $table->unsignedInteger('submit_time')->nullable()->comment('提交时间');
            $table->unsignedInteger('audit_time')->nullable()->comment('审核时间');
            $table->unsignedInteger('cancel_time')->nullable()->comment('取消时间');
            $table->unsignedInteger('admin_id')->nullable()->index()->comment('系统管理员ID');
            $table->string('remark', 255)->nullable()->comment('任务备注');
            $table->unsignedInteger('created_at')->comment('创建时间');
            $table->unsignedInteger('updated_at')->comment('更新时间');
        });

        DB::statement('ALTER TABLE `user_task` COMMENT = "用户任务记录表";');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_task');
    }
}
