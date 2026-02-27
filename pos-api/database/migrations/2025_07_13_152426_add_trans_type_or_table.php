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
        Schema::table('official_receipt_details', function (Blueprint $table) {
            $table->string('trans_type', 15)->nullable();
            $table->string('ref_no', 25)->nullable();
            $table->decimal('tax_rate', 18, 4)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('official_receipt_details', function (Blueprint $table) {
            $table->dropColumn(['trans_type', 'ref_no', 'tax_rate']);
        });
    }
};
