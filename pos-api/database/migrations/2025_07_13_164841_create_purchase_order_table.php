<?php

use App\Enums\PoStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('order_no', 25)->unique();
            $table->datetime('date');
            $table->string('remarks', 250)->nullable();
            $table->foreignId('file_id')->nullable()->constrained('files');
            $table->string("vendor_idno", 35);
            $table->string("vendor_name", 120)->nullable();
            $table->string("vendor_email", 120)->nullable();
            $table->string("billing_address", 250)->nullable();
            $table->string("status", 250)->default(PoStatus::OPEN)->nullable();
            $table->decimal("amount", 18, 4);
            $table->foreignId('creator_id')->constrained('users');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('order_id')->constrained('orders');
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('product_expense_id')->constrained('chart_accounts');
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
        Schema::dropIfExists('order_details');
        Schema::dropIfExists('orders');
    }
};
