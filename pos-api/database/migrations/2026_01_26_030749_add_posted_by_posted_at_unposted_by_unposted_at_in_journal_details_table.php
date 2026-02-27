<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    protected array $tables = [
        'journal_details',
        'official_receipt_details',
        'payment_details',
        'invoice_details',
        'bill_details',
    ];

    public function up(): void
    {
        foreach ($this->tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->foreignId('posted_by')
                    ->nullable()
                    ->constrained('users')
                    ->restrictOnDelete();

                $table->timestamp('posted_at')
                    ->nullable();

                $table->foreignId('unposted_by')
                    ->nullable()
                    ->constrained('users')
                    ->restrictOnDelete();

                $table->timestamp('unposted_at')
                    ->nullable();
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropForeign(['posted_by']);
                $table->dropForeign(['unposted_by']);

                $table->dropColumn([
                    'posted_by',
                    'posted_at',
                    'unposted_by',
                    'unposted_at',
                ]);
            });
        }
    }
};
