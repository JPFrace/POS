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
        Schema::create('financial_trans_codes', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('trans_type', 25);
            $table->string('code', 25);
            $table->string('name', 250);
            $table->string('description', 350);
            $table->foreignId('creator_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_trans_codes');
    }
};
