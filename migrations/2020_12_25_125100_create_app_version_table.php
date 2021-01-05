<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateAppVersionTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('app_version', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100)->comment('版本名称');
            $table->string('version', 20)->comment('版本');
            $table->unsignedInteger('version_number')->comment('版本编号');
            $table->text('description')->comment('版本更新详情');
            $table->unsignedTinyInteger('is_mandatory')->comment('是否强制更新');
            $table->string('download_url')->comment('更新地址');
            $table->unsignedTinyInteger('update_mode')->comment('更新模式: 1=热更新; 2=覆盖更新');
            $table->unsignedInteger('update_time')->comment('指定更新时间');
            $table->unsignedInteger('created_at')->comment('创建时间');
            $table->unsignedInteger('updated_at')->comment('更新时间');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_version');
    }
}
