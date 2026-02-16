<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('email_logs', function (Blueprint $table) {
            $table->unsignedInteger('duration_expiring_contracts_count')->default(0)->after('low_budget_contracts_count');
            $table->json('expiring_contract_ids')->nullable()->after('duration_expiring_contracts_count');
            $table->json('low_budget_contract_ids')->nullable()->after('expiring_contract_ids');
            $table->json('duration_expiring_contract_ids')->nullable()->after('low_budget_contract_ids');
        });
    }

    public function down(): void
    {
        Schema::table('email_logs', function (Blueprint $table) {
            $table->dropColumn([
                'duration_expiring_contracts_count',
                'expiring_contract_ids',
                'low_budget_contract_ids',
                'duration_expiring_contract_ids',
            ]);
        });
    }
};
