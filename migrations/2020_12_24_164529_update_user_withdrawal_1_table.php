<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class UpdateUserWithdrawal1Table extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_withdrawal', function (Blueprint $table) {
            $table->unsignedInteger('integral')->default(0)->comment('本次提现消耗积分');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_withdrawal', function (Blueprint $table) {
            //
        });
    }
}
