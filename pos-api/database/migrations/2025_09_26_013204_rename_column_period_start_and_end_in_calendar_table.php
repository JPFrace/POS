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
        Schema::rename('calendar', 'calendars');
        Schema::table('calendars', function (Blueprint $table) {
            $table->renameColumn('period_start', 'start_date');
            $table->renameColumn('period_end', 'end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('calendars', 'calendar');
        Schema::table('calendars', function (Blueprint $table) {
            $table->renameColumn('start_date', 'period_start');
            $table->renameColumn('end_date', 'period_end');
        });
    }
};
