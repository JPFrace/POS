<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tax_setup', function (Blueprint $table) {
            // Add UUID column as nullable first
            $table->uuid('uuid')->nullable()->after('id');
        });

        // Populate existing rows with UUIDs
        $taxSetups = DB::table('tax_setup')->whereNull('uuid')->get();
        foreach ($taxSetups as $tax) {
            DB::table('tax_setup')
                ->where('id', $tax->id)
                ->update(['uuid' => (string) Str::uuid()]);
        }

        // Make the column non-nullable and unique
        Schema::table('tax_setup', function (Blueprint $table) {
            $table->uuid('uuid')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tax_setup', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
