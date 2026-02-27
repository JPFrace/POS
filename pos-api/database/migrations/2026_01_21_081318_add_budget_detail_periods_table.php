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
        if (!Schema::hasTable('budget_detail_periods')) {
            Schema::create('budget_detail_periods', function (Blueprint $table) {
                $table->id();
                $table->uuid();
                $table->foreignId('budget_detail_id')->constrained('budget_details')->onDelete('cascade');
                $table->decimal('period_1', 10, 2);
                $table->decimal('period_2', 10, 2);
                $table->decimal('period_3', 10, 2);
                $table->decimal('period_4', 10, 2);
                $table->decimal('period_5', 10, 2);
                $table->decimal('period_6', 10, 2);
                $table->decimal('period_7', 10, 2);
                $table->decimal('period_8', 10, 2);
                $table->decimal('period_9', 10, 2);
                $table->decimal('period_10', 10, 2);
                $table->decimal('period_11', 10, 2);
                $table->decimal('period_12', 10, 2);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_detail_periods');
    }
};
