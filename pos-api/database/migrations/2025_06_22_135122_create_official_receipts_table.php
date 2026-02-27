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
        Schema::create('official_receipts', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string("type_id");
            $table->string('or_no', 25)->unique();
            $table->string('ref_no', 25);
            $table->datetime('date');
            $table->string('remarks', 250)->nullable();
            $table->foreignId('file_id')->nullable()->constrained('files');
            $table->string("customer_idno", 35);
            $table->string("customer_name", 120)->nullable();
            $table->string("customer_email", 120)->nullable();
            $table->string("billing_address", 250)->nullable();
            $table->decimal("amount", 18, 4);
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_types');
            $table->foreignId('deposit_id')->constrained('chart_accounts');
            $table->foreignId('creator_id')->constrained('users');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('official_receipt_details', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('or_id')->constrained('official_receipts');
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('product_income_id')->constrained('chart_accounts');
            $table->unsignedInteger('quantity')->nullable();
            $table->decimal('rate', 18, 4)->nullable();
            $table->string('product_name', 120)->nullable();
            $table->string('product_description', 250)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('official_receipt_details');
        Schema::dropIfExists('official_receipts');
    }
};
