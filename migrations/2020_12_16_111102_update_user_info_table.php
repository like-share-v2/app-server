<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class UpdateUserInfoTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_info', function (Blueprint $table) {
            $table->string('phone', 50)->nullable()->default(null)->comment('银行手机');
            $table->string('email', 100)->nullable()->default(null)->comment('邮箱');
            $table->string('upi', 50)->nullable()->default(null)->comment('UPI');
            $table->string('ifsc', 50)->nullable()->default(null)->comment('IFSC');
            $table->dropColumn('bank_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_info', function (Blueprint $table) {
            //
        });
    }
}
