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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->uuid();

            $table->string('sku', 100)->unique();
            $table->string('name',100);

            // Assuming the category_id is a foreign key to a product_categories table
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('product_categories');

            $table->text('description')->nullable();
            $table->decimal('price', 18, 4)->default(0.00);

            // Assuming the income_id is a foreign key to a chart_accounts table where account_categories is 'REVENUE'
            $table->unsignedBigInteger('income_id');
            $table->foreign('income_id')->references('id')->on('chart_accounts')->onDelete('no action');

            // Assuming the photo_id is a foreign key to a files table
            $table->unsignedBigInteger('photo_id')->nullable();
            $table->foreign('photo_id')->references('id')->on('files');

            $table->text('purchase_description')->nullable();
            $table->decimal('cost', 18, 4)->default(0.00)->nullable();

            // Assuming the expense_id is a foreign key to a chart_accounts table where account_categories is 'EXPENSE'
            $table->unsignedBigInteger('expense_id')->nullable();
            $table->foreign('expense_id')->references('id')->on('chart_accounts');
            
            // Assuming the vendor_id is a foreign key to a contacts table where type is 'VENDOR'
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->foreign('vendor_id')->references('id')->on('contacts');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
