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
        Schema::create('contact_classes', function (Blueprint $table) {
            $table->id();

            $table->string('name', 150);
            $table->foreignId('receivable_id')->nullable()->constrained('chart_accounts');
            $table->foreignId('payable_id')->nullable()->constrained('chart_accounts');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('contacts', function (Blueprint $table) {
            $table->foreignId('class_id')->nullable()->constrained('contact_classes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_classes');
    }
};
