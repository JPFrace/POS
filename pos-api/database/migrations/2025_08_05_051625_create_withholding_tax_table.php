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
        Schema::create('withholding_tax_types', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('code', 25)->unique();
            $table->string('name', 120);
            $table->string('description', 255);
            $table->timestamps();
        });

        Schema::create('withholding_taxes', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('code', 25)->unique();
            $table->string('description')->nullable();
            $table->integer('rate')->default(0);
            $table->foreignId('type_id')->constrained('withholding_tax_types');
            $table->foreignId('payer_type_id')->constrained('contact_sub_types');
            $table->boolean('is_inactive')->default(false);
            $table->foreignId('created_by')->constrained('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withholding_taxes');
        Schema::dropIfExists('withholding_tax_types');
    }
};
