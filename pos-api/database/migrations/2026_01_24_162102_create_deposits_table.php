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
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('ref_no', 20)->unique();
            $table->dateTime('date');
            $table->string('remarks', 250)->nullable();
            $table->foreignId('file_id')->nullable()->constrained('files');
            $table->foreignId('cash_bank_id')->references('id')->on('chart_accounts')->noActionOnDelete();
            $table->decimal("amount", 18, 4);
            $table->foreignId('creator_id')->constrained('users');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('deposit_details', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('deposit_id')->references('id')->on('deposits')->noActionOnDelete();
            $table->morphs('transactable');
            $table->dateTime('date');
            $table->string('contact_idno');
            $table->foreignId('payment_method_id')->references('id')->on('payment_types')->noActionOnDelete();
            $table->string('memo', 120)->nullable();
            $table->string('ref_no', 250)->nullable();
            $table->decimal('rate', 18, 4);
            $table->softDeletes();
            $table->timestamps();
        });


        Schema::table('official_receipts', function (Blueprint $table) {
            $table->decimal('gross_amount', 18, 4)->default(0);
            $table->decimal('actual_receive_amount', 18, 4)->default(0);
            $table->dateTime('deposited_at')->nullable();
            $table->dateTime('deposit_transit_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposit_details');
        Schema::dropIfExists('deposits');

        Schema::table('official_receipts', function (Blueprint $table) {
            $table->dropColumn(['gross_amount', 'actual_receive_amount', 'deposit_transit_at', 'deposited_at']);
        });
    }
};
