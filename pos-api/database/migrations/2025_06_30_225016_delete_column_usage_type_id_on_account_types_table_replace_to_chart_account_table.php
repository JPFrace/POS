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
        Schema::table('account_types', function (Blueprint $table) {
            $table->dropForeign('account_types_usage_type_id_foreign');
            $table->dropColumn('usage_type_id');
        });

        Schema::table('chart_accounts', function (Blueprint $table) {
            $table->foreignId('usage_type_id')->nullable()->references('id')->on('account_usage_types')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chart_accounts', function (Blueprint $table) {
            $table->dropForeign('chart_accounts_usage_type_id_foreign');
            $table->dropColumn('usage_type_id');
        });
    }
};
