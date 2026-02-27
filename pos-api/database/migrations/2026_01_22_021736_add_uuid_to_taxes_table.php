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
        Schema::table('taxes', function (Blueprint $table) {
            if (!Schema::hasColumn('taxes', 'uuid')) {
                $table->uuid('uuid')->nullable()->after('id');
            }
        });

        $taxes = DB::table('taxes')->whereNull('uuid')->get();
        foreach ($taxes as $tax) {
            DB::table('taxes')
                ->where('id', $tax->id)
                ->update(['uuid' => (string) Str::uuid()]);
        }

        Schema::table('taxes', function (Blueprint $table) {
            // $table->uuid('uuid')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('taxes', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
