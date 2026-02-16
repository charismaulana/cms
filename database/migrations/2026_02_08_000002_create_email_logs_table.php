<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_recipient_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('contract_id')->nullable()->constrained()->onDelete('set null');
            $table->string('recipient_email');
            $table->string('recipient_name')->nullable();
            $table->string('field')->nullable();
            $table->string('fungsi')->nullable();
            $table->string('subject');
            $table->enum('status', ['sent', 'failed'])->default('sent');
            $table->text('error_message')->nullable();
            $table->integer('expiring_contracts_count')->default(0);
            $table->integer('low_budget_contracts_count')->default(0);
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            // Index for filtering
            $table->index(['status', 'sent_at']);
            $table->index(['field', 'fungsi']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};
