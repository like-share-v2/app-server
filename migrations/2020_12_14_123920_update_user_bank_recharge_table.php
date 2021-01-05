<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class UpdateUserBankRechargeTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_bank_recharge', function (Blueprint $table) {
            $table->string('voucher')->comment('转账凭证');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_bank_recharge', function (Blueprint $table) {
            //
        });
    }
}
