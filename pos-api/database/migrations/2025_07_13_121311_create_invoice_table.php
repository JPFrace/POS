<?php

use App\Enums\InvoiceStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('invoice_no', 25)->unique();
            $table->datetime('date');
            $table->datetime('due_date')->nullable();
            $table->string('remarks', 250)->nullable();
            $table->foreignId('file_id')->nullable()->constrained('files');
            $table->string("customer_idno", 35);
            $table->string("customer_name", 120)->nullable();
            $table->string("customer_email", 120)->nullable();
            $table->string("billing_address", 250)->nullable();
            $table->string("status", 250)->default(InvoiceStatusEnum::UNPOSTED)->nullable();
            $table->decimal("amount", 18, 4);
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_types');
            $table->foreignId('creator_id')->constrained('users');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('invoice_details', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('invoice_id')->constrained('invoices');
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('product_income_id')->constrained('chart_accounts');
            $table->unsignedInteger('quantity')->nullable();
            $table->decimal('rate', 18, 4)->nullable();
            $table->decimal('tax_rate', 18, 4)->nullable();
            $table->decimal('paid', 18, 4)->nullable();
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
        Schema::dropIfExists('invoice_details');
        Schema::dropIfExists('invoices');
    }
};
