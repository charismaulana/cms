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
        Schema::create('contract_realizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained()->onDelete('cascade');
            $table->integer('month');
            $table->integer('year');
            $table->string('pr_number')->nullable();
            $table->string('po_number')->nullable();
            $table->string('sa_number')->nullable();
            $table->date('sa_date')->nullable();
            $table->decimal('realization_value', 20, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_realizations');
    }
};
