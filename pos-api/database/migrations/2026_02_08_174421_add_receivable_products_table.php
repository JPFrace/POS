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
        Schema::table("products", function (Blueprint $table) {
            $table->foreignId("receivable_id")->nullable()->constrained("chart_accounts");
        });

        Schema::table("invoice_details", function (Blueprint $table) {
            $table->foreignId("product_receivable_id")->nullable()->constrained("chart_accounts");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("products", function (Blueprint $table) {
            $table->dropConstrainedForeignId("receivable_id");
        });

        Schema::table("invoice_details", function (Blueprint $table) {
            $table->dropConstrainedForeignId("product_receivable_id");
        });
    }
};
