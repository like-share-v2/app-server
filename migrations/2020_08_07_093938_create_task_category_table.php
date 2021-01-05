<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;
use Hyperf\DbConnection\Db;

class CreateTaskCategoryTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('task_category', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 30)->comment('任务分类名');
            $table->string('icon', 255)->comment('任务分类图标');
            $table->string('banner', 255)->comment('任务顶部大图');
            $table->unsignedInteger('sort')->default(0)->comment('排序');
            $table->unsignedInteger('status')->default(1)->comment('分类状态 1=正常 0=禁用');
            $table->longText('job_step')->comment('任务步骤');
            $table->longText('audit_sample')->comment('审核样例');
            $table->unsignedInteger('created_at')->comment('创建时间');
            $table->unsignedInteger('updated_at')->comment('更新时间');
        });

        DB::statement('ALTER TABLE `task_category` COMMENT = "任务分类表";');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_category');
    }
}
