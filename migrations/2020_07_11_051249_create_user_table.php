<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;
use Hyperf\DbConnection\Db;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('parent_id')->index()->comment('上级ID');
            $table->unsignedInteger('level')->default(0)->index()->comment('会员等级');
            $table->string('account', 50)->nullable()->default(null)->unique()->comment('登录账号');
            $table->string('password', 255)->nullable()->default(null)->comment('登录密码');
            $table->string('phone', 50)->nullable()->default(null)->unique()->comment('手机号码');
            $table->string('email', 255)->nullable()->default(null)->unique()->comment('电子邮箱');
            $table->string('nickname', 50)->nullable()->default(null)->comment('昵称');
            $table->string('avatar', 255)->nullable()->default(null)->comment('头像');
            $table->unsignedInteger('gender')->default(0)->comment('性别 0=保密 1=男 2=女');
            $table->unsignedDecimal('balance', 11, 2)->default(0)->comment('余额');
            $table->unsignedInteger('integral')->default(0)->comment('积分');
            $table->unsignedInteger('credit')->default(0)->comment('信用分');
            $table->unsignedTinyInteger('status')->default(1)->comment('账号状态: 0=禁用;1=正常');
            $table->unsignedInteger('last_login_time')->default(null)->nullable()->comment('上次登录时间');
            $table->unsignedInteger('created_at')->comment('创建时间');
            $table->unsignedInteger('updated_at')->comment('更新时间');
        });
        DB::statement('ALTER TABLE `user` COMMENT = "用户表";');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
}
