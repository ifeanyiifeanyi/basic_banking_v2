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
        Schema::table('account_types', function (Blueprint $table) {
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->decimal('minimum_balance', 15, 2)->default(0);
            $table->decimal('interest_rate', 5, 2)->default(0);
            $table->boolean('is_active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('account_types', function (Blueprint $table) {
            $table->dropColumn(['code', 'description', 'minimum_balance', 'interest_rate', 'is_active']);
        });
    }
};
