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
        // Pivot table for email recipient field/fungsi assignments
        Schema::create('email_recipient_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_recipient_id')->constrained()->onDelete('cascade');
            $table->string('field')->nullable(); // zone_1_2, zone_3, etc.
            $table->string('fungsi')->nullable(); // general_services, warehouse
            $table->timestamps();

            // Unique constraint to prevent duplicates
            $table->unique(['email_recipient_id', 'field', 'fungsi'], 'recipient_field_fungsi_unique');
        });

        // Add is_global flag to email_recipients
        Schema::table('email_recipients', function (Blueprint $table) {
            $table->boolean('is_global')->default(false)->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_recipient_assignments');

        Schema::table('email_recipients', function (Blueprint $table) {
            $table->dropColumn('is_global');
        });
    }
};
