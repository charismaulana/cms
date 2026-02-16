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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->enum('field', [
                'zona_4',
                'prabumulih',
                'limau',
                'adera',
                'ramba',
                'pendopo',
                'okrt'
            ]);
            $table->string('contract_number')->unique();
            $table->string('title');
            $table->string('vendor_name');
            $table->string('vendor_number')->nullable();
            $table->enum('status', [
                'kontrak_awal',
                'amandemen_1',
                'amandemen_2',
                'amandemen_3',
                'amandemen_4'
            ])->default('kontrak_awal');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('total_value', 20, 2);
            $table->decimal('realization_value', 20, 2)->default(0);
            $table->date('last_realization_update')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
