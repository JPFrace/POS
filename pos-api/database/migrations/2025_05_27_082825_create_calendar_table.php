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
        Schema::create('calendar', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('year')->comment('Year of the calendar');
            $table->integer('no_of_periods');
            $table->date('period_start');
            $table->date('period_end');
            $table->date('period_1');
            $table->boolean('period_1_closed')->default(false);
            $table->date('period_2');
            $table->boolean('period_2_closed')->default(false);
            $table->date('period_3');
            $table->boolean('period_3_closed')->default(false);
            $table->date('period_4');     
            $table->boolean('period_4_closed')->default(false);
            $table->date('period_5');
            $table->boolean('period_5_closed')->default(false);
            $table->date('period_6');
            $table->boolean('period_6_closed')->default(false);
            $table->date('period_7');
            $table->boolean('period_7_closed')->default(false);
            $table->date('period_8');
            $table->boolean('period_8_closed')->default(false);
            $table->date('period_9');
            $table->boolean('period_9_closed')->default(false);
            $table->date('period_10');
            $table->boolean('period_10_closed')->default(false);
            $table->date('period_11');
            $table->boolean('period_11_closed')->default(false);
            $table->date('period_12');
            $table->boolean('period_12_closed')->default(false);
            $table->boolean('is_inactive');
            $table->integer('created_by')->nullable()->comment('ID of the user who created the calendar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar');
    }
};
