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
        Schema::create('reconciliations', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->dateTime('start_at');
            $table->dateTime('end_at')->nullable();
            $table->decimal('bank_statement_ending_balance', 18, 4);
            $table->decimal('ending_balance', 18, 4);
            $table->foreignId('account_id')->constrained('chart_accounts');
            $table->dateTime('closed_at')->nullable();
            $table->foreignId('closed_by')->nullable()->constrained('users');
            $table->foreignId('calendar_id')->constrained('calendars');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reconciliations');
    }
};
