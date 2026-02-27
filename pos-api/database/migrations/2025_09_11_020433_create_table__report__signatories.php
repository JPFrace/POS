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
        Schema::create('report_signatories', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('report_id')->nullable()->constrained('reports');
            $table->string('label', 50)->nullable();
            $table->foreignId('signatory_id')->nullable()->constrained('signatories');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->boolean('is_inactive')->default(false);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_signatories');
    }
};
