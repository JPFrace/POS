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
        Schema::table("payment_details", function (Blueprint $table) {
            $table->foreignId("tax_account_id")->nullable()->constrained("chart_accounts");
        });

        Schema::table("official_receipt_details", function (Blueprint $table) {
            $table->foreignId("tax_account_id")->nullable()->constrained("chart_accounts");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("payment_details", function (Blueprint $table) {
            $table->dropConstrainedForeignId("tax_account_id");
        });

        Schema::table("official_receipt_details", function (Blueprint $table) {
            $table->dropConstrainedForeignId("tax_account_id");
        });
    }
};
