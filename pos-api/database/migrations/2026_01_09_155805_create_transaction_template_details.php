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
        Schema::create('transaction_template_details', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->foreignId('template_id')
                ->constrained('transaction_templates')
                ->restrictOnDelete();
            $table->foreignId('product_id')
                ->constrained('products')
                ->restrictOnDelete();
            $table->decimal("amount", 15, 3)->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->foreignId('creator_id')
                ->constrained('users')
                ->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_template_details');
    }
};
