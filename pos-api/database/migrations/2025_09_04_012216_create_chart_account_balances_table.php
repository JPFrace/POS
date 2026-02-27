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
        Schema::create('chart_account_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chart_account_id')->constrained("chart_accounts");
            $table->date('start_at');
            $table->date('end_at');
            $table->decimal('beginning')->default(0)->nullable();
            $table->decimal('ending')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chart_account_balances');
    }
};
