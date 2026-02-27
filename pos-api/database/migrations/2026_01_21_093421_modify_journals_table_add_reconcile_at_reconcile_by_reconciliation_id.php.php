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
        Schema::table("journals", function (Blueprint $table) {
            $table->dateTime('reconcile_at')->nullable();
            $table->foreignID('reconcile_by')->nullable()->constrained('users');
            $table->foreignId('reconciliation_id')->nullable()->constrained('reconciliations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('journals', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['reconcile_by']);
            $table->dropForeign(['reconciliation_id']);

            // Drop columns
            $table->dropColumn([
                'reconcile_at',
                'reconcile_by',
                'reconciliation_id'
            ]);
        });
    }
};
