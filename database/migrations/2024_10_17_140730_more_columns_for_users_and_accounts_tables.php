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
            // Account management
            $table->boolean('can_transfer')->default(true);
            $table->boolean('can_receive')->default(true);
            $table->boolean('is_archived')->default(false);
            $table->timestamp('archived_at')->nullable();
            $table->unsignedBigInteger('archived_by')->nullable();
            $table->string('suspension_reason')->nullable();
            $table->timestamp('suspended_at')->nullable();
            $table->unsignedBigInteger('suspended_by')->nullable();

            // Security and compliance
            // $table->enum('kyc_status', ['pending', 'approved', 'rejected'])->default('pending');
            // $table->timestamp('kyc_verified_at')->nullable();
            // $table->unsignedBigInteger('kyc_verified_by')->nullable();
            // $table->timestamp('last_activity')->nullable();
            // $table->string('device_id')->nullable();
            // $table->json('allowed_devices')->nullable();

            // Foreign keys
            $table->foreign('archived_by')->references('id')->on('users');
            $table->foreign('suspended_by')->references('id')->on('users');
            // $table->foreign('kyc_verified_by')->references('id')->on('users');
        });

        Schema::table('accounts', function (Blueprint $table) {
            $table->boolean('is_suspended')->default(false);
            $table->string('suspension_reason')->nullable();
            $table->timestamp('suspended_at')->nullable();
            $table->unsignedBigInteger('suspended_by')->nullable();
            $table->timestamp('reactivated_at')->nullable();
            $table->unsignedBigInteger('reactivated_by')->nullable();
            // $table->string('account_type')->default('savings');
            $table->decimal('daily_transfer_limit', 15, 2)->nullable();
            $table->decimal('monthly_transfer_limit', 15, 2)->nullable();
            $table->boolean('requires_approval')->default(false);
            $table->softDeletes();

            // Foreign keys
            $table->foreign('suspended_by')->references('id')->on('users');
            $table->foreign('reactivated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
