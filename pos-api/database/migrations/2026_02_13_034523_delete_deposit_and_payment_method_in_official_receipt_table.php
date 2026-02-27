<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tableName = 'official_receipts';
        $schemaName = DB::getDatabaseName();

        $foreignKeys = DB::select(
            "SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME IN ('deposit_id', 'payment_method_id')
             AND REFERENCED_TABLE_NAME IS NOT NULL",
            [$schemaName, $tableName]
        );

        foreach ($foreignKeys as $fk) {
            Schema::table($tableName, function (Blueprint $table) use ($fk) {
                $table->dropForeign($fk->CONSTRAINT_NAME);
            });
        }

        $columnsToDrop = array_filter(
            ['deposit_id', 'payment_method_id'],
            fn (string $col) => Schema::hasColumn($tableName, $col)
        );
        if (! empty($columnsToDrop)) {
            Schema::table($tableName, function (Blueprint $table) use ($columnsToDrop) {
                $table->dropColumn($columnsToDrop);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('official_receipts', function (Blueprint $table) {
            $table->unsignedBigInteger('deposit_id')->nullable();
            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->foreign('deposit_id')->references('id')->on('chart_accounts');
            $table->foreign('payment_method_id')->references('id')->on('payment_types');
        });
    }
};
