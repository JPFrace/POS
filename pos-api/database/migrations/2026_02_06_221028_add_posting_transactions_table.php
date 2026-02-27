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
        Schema::table("official_receipts", function (Blueprint $table) {
            $table->dateTime('posted_at')->nullable();
            $table->foreignId('posted_by')->nullable()->constrained('users');

            $table->dateTime('unposted_at')->nullable();
            $table->foreignId('unposted_by')->nullable()->constrained('users');
        });

        Schema::table("payments", function (Blueprint $table) {
            $table->dateTime('posted_at')->nullable();
            $table->foreignId('posted_by')->nullable()->constrained('users');

            $table->dateTime('unposted_at')->nullable();
            $table->foreignId('unposted_by')->nullable()->constrained('users');
        });

        Schema::table("invoices", function (Blueprint $table) {
            $table->dateTime('posted_at')->nullable();
            $table->foreignId('posted_by')->nullable()->constrained('users');

            $table->dateTime('unposted_at')->nullable();
            $table->foreignId('unposted_by')->nullable()->constrained('users');
        });

        Schema::table("bills", function (Blueprint $table) {
            $table->dateTime('posted_at')->nullable();
            $table->foreignId('posted_by')->nullable()->constrained('users');

            $table->dateTime('unposted_at')->nullable();
            $table->foreignId('unposted_by')->nullable()->constrained('users');
        });

        Schema::table("deposits", function (Blueprint $table) {
            $table->dateTime('posted_at')->nullable();
            $table->foreignId('posted_by')->nullable()->constrained('users');

            $table->dateTime('unposted_at')->nullable();
            $table->foreignId('unposted_by')->nullable()->constrained('users');
        });

        Schema::table("journal_entries", function (Blueprint $table) {
            $table->dateTime('posted_at')->nullable();
            $table->foreignId('posted_by')->nullable()->constrained('users');

            $table->dateTime('unposted_at')->nullable();
            $table->foreignId('unposted_by')->nullable()->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("official_receipts", function (Blueprint $table) {
            $table->dropColumn(['posted_at', 'unposted_at']);
            $table->dropConstrainedForeignId('posted_by');
            $table->dropConstrainedForeignId('unposted_by');
        });

        Schema::table("payments", function (Blueprint $table) {
            $table->dropColumn(['posted_at', 'unposted_at']);
            $table->dropConstrainedForeignId('posted_by');
            $table->dropConstrainedForeignId('unposted_by');
        });

        Schema::table("invoices", function (Blueprint $table) {
            $table->dropColumn(['posted_at', 'unposted_at']);
            $table->dropConstrainedForeignId('posted_by');
            $table->dropConstrainedForeignId('unposted_by');
        });

        Schema::table("bills", function (Blueprint $table) {
            $table->dropColumn(['posted_at', 'unposted_at']);
            $table->dropConstrainedForeignId('posted_by');
            $table->dropConstrainedForeignId('unposted_by');
        });

        Schema::table("deposits", function (Blueprint $table) {
            $table->dropColumn(['posted_at', 'unposted_at']);
            $table->dropConstrainedForeignId('posted_by');
            $table->dropConstrainedForeignId('unposted_by');
        });

        Schema::table("journal_entries", function (Blueprint $table) {
            $table->dropColumn(['posted_at', 'unposted_at']);
            $table->dropConstrainedForeignId('posted_by');
            $table->dropConstrainedForeignId('unposted_by');
        });
    }
};
