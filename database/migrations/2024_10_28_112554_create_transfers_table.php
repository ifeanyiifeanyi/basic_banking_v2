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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->uuid('reference')->unique();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('from_account_id')->constrained('accounts');
            $table->string('to_account_number');
            $table->foreignId('bank_id')->nullable()->constrained(); // Only for external transfers
            $table->decimal('amount', 20, 2);
            $table->string('transfer_type'); // internal, external
            $table->string('status')->default('pending'); // pending, completed, failed
            $table->json('meta_data')->nullable(); // Store external transfer requirements
            $table->text('narration')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
