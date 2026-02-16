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
        Schema::create('contract_amendments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained()->onDelete('cascade');
            $table->integer('amendment_number'); // 1, 2, 3, etc. for Amandemen I, II, III
            $table->enum('amendment_type', ['value_only', 'time_only', 'value_and_time']);
            $table->boolean('is_bridging')->default(false);
            $table->decimal('added_value', 20, 2)->nullable(); // Additional value
            $table->date('new_end_date')->nullable(); // New end date if time extended
            $table->text('notes')->nullable();
            $table->timestamps();

            // Ensure unique amendment numbers per contract
            $table->unique(['contract_id', 'amendment_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_amendments');
    }
};
