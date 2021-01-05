<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;
use Hyperf\DbConnection\Db;

class CreateRechargeQrCodeTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('recharge_qr_code', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image', 255)->comment('收款二维码');
            $table->unsignedInteger('status')->comment('状态 0=禁用 1=正常');
            $table->unsignedInteger('created_at')->comment('创建时间');
            $table->unsignedInteger('updated_at')->comment('更新时间');
        });

        Db::statement('ALTER TABLE `recharge_qr_code` COMMENT = "扫码充值二维码表";');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recharge_qr_code');
    }
}
