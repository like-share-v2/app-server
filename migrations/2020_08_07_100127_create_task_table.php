<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;
use Hyperf\DbConnection\Db;

class CreateTaskTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('task', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('category_id')->index()->comment('分类ID');
            $table->unsignedInteger('level')->index()->comment('会员等级');
            $table->string('title', 100)->comment('任务标题');
            $table->string('description', 255)->comment('任务描述');
            $table->string('url',255)->comment('任务地址');
            $table->unsignedDecimal('amount', 11, 2)->comment('任务金额');
            $table->unsignedInteger('num')->comment('发放数量');
            $table->unsignedInteger('status')->default(1)->index()->comment('任务状态 1=正常 0=禁用');
            $table->unsignedInteger('sort')->comment('排序');
            $table->unsignedInteger('created_at')->comment('添加时间');
            $table->unsignedInteger('updated_at')->comment('更新时间');
            $table->unsignedInteger('deleted_at')->nullable()->comment('软删除');
        });

        DB::statement('ALTER TABLE `task` COMMENT = "任务表";');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task');
    }
}
