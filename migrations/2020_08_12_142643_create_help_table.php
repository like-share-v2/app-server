<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;
use Hyperf\DbConnection\Db;

class CreateHelpTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('help', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', '50')->comment('标题');
            $table->text('content')->comment('内容');
            $table->unsignedInteger('status')->index()->comment('状态');
            $table->unsignedInteger('sort')->comment('排序');
            $table->unsignedInteger('created_at')->comment('创建时间');
            $table->unsignedInteger('updated_at')->comment('更新时间');
        });

        Db::statement('ALTER TABLE `help` COMMENT = "帮助手册表";');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('help');
    }
}
