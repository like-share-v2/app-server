<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class DeleteExchangeRate extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('country', function (Blueprint $table) {
            $table->dropColumn('exchange_rate');
        });
        Schema::table('defray', function (Blueprint $table) {
            $table->dropColumn('exchange_rate');
        });
        Schema::table('user_bank_recharge', function (Blueprint $table) {
            $table->dropColumn('exchange_rate');
        });
        Schema::table('user_online_recharge', function (Blueprint $table) {
            $table->dropColumn('exchange_rate');
        });
        Schema::table('user_withdrawal', function (Blueprint $table) {
            $table->dropColumn('exchange_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('', function (Blueprint $table) {
            //
        });
    }
}
