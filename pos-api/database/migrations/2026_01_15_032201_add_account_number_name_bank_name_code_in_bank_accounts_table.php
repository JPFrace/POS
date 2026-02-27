<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bank_accounts', function (Blueprint $table) {
            if (!Schema::hasColumn('bank_accounts', 'account_number')) {
            $table->string("account_number", 100);
            }
            if (!Schema::hasColumn('bank_accounts', 'account_name')) {
            $table->string("account_name", 100);
            }
            if (!Schema::hasColumn('bank_accounts', 'bank_name')) {
            $table->string("bank_name", 100);
            }
            if (!Schema::hasColumn('bank_accounts', 'bank_code')) {
            $table->string("bank_code", 50);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->dropColumn(['account_number', 'account_name', 'bank_name', 'bank_code']);
        });
    }
};
