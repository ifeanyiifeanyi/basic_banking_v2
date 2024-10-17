<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->date('dob')->nullable();
            $table->string('gender', 10)->nullable();
            $table->string('occupation', 100)->nullable();
            $table->date('last_login')->nullable();
            $table->string('last_ip', 50)->nullable();
            $table->string('last_location', 100)->nullable();
            $table->unsignedBigInteger('login_attempts')->default(0);
            $table->timestamp('lockout_until')->nullable();
            $table->unsignedBigInteger('failed_login_attempts')->default(0);
            $table->string('two_factor_secret')->nullable();
            $table->timestamp('two_factor_recovery_codes')->nullable();
            $table->string('two_factor_enabled', 10)->default('disabled');
            $table->string('two_factor_code', 10)->default('disabled');
            $table->boolean('account_status')->default(true);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
