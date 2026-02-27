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
        Schema::create('dimensions', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->timestamps();
            $table->softDeletes();
            $table->string('name', 150)->unique();
            $table->string('description', 255)->nullable();
            $table->boolean('is_inactive')->default(false);
        });

        Schema::create('transaction_dimensions', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->timestamps();
            $table->foreignId('trans_type')->constrained('transaction_types');
            $table->morphs('transactable');
            $table->foreignId('dimension_id')->constrained('dimensions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_dimensions');
        Schema::dropIfExists('dimensions');
    }
};
