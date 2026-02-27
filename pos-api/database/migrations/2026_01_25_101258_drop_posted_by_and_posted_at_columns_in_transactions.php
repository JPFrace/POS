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
        $tables = ['journal_entries', 'official_receipts', 'payments', 'invoices', 'bills'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                // Modern Laravel way to drop foreign key and column in one go
                if (Schema::hasColumn($table->getTable(), 'posted_by')) {
                    $table->dropConstrainedForeignId('posted_by');
                }

                if (Schema::hasColumn($table->getTable(), 'posted_at')) {
                    $table->dropColumn('posted_at');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['journal_entries', 'official_receipts', 'payments', 'invoices', 'bills'];

        foreach ($tables as $tables) {
            Schema::table($tables, function (Blueprint $table) {
                $table->foreignId('posted_by')
                    ->nullable()
                    ->after('status_id')
                    ->constrained('users')
                    ->restrictOnDelete();

                $table->timestamp('posted_at')->nullable()->after('posted_by');
            });
        }
    }
};
