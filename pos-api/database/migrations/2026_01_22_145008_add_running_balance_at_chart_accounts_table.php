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
        Schema::table("chart_accounts", function (Blueprint $table) {
            $table->dateTime("run_balance_at")->nullable();
            $table->dateTime("start_track_balance_at")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("chart_accounts", function (Blueprint $table) {
            $table->dropColumn(["run_balance_at", "start_track_balance_at"]);
        });
    }
};
