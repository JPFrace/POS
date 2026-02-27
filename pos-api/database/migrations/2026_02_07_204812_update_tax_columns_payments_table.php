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
            $table->renameColumn("tax_account_id", "withholding_tax_account_id");
            $table->foreignId("purchase_tax_account_id")->nullable()->after('withholding_tax_account_id')->constrained('chart_accounts');

            $table->renameColumn("tax_rate", "withholding_tax_rate");
            $table->decimal('purchase_tax_rate', 18, 4)->nullable()->after("withholding_tax_rate");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("payment_details", function (Blueprint $table) {
            $table->renameColumn("withholding_tax_account_id", "tax_account_id");
            $table->dropConstrainedForeignId("purchase_tax_account_id");

            $table->renameColumn("withholding_tax_rate", "tax_rate");
            $table->dropColumn("purchase_tax_rate");
        });
    }
};
