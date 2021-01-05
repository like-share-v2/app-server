<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;
use Hyperf\DbConnection\Db;

class CreateUserRelationTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_relation', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->index()->comment('用户ID');
            $table->unsignedInteger('parent_id')->index()->comment('上级ID');
            $table->unsignedInteger('level')->index()->comment('代理等级');
            $table->unsignedInteger('created_at')->comment('创建时间');
        });

        DB::statement('ALTER TABLE `user_relation` COMMENT = "用户关系表";');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_relation');
    }
}
