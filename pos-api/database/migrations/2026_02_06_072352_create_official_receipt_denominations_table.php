<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('official_receipt_denominations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('or_id')
                ->constrained('official_receipts')
                ->cascadeOnDelete();

            $table->foreignId('deposit_id')
                ->constrained('chart_accounts');

            $table->foreignId('payment_method_id')
                ->constrained('payment_types');

            $table->unsignedInteger('quantity')->default(0);
            $table->decimal('denomination', 12, 2)->unsigned();

            $table->string('bank', 50)->nullable();
            $table->date('reference_date')->nullable();
            $table->string('reference_no', 50)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('official_receipt_denominations');
    }
};
