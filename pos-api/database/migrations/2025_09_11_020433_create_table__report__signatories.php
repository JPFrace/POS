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
        if (Schema::hasTable('report_signatories')) {
            return;
        }

        Schema::create('report_signatories', function (Blueprint $table) {
            $table->id();
            $table->uuid();

            $table->unsignedBigInteger('report_id')->nullable();
            $table->string('label', 50)->nullable();
            $table->unsignedBigInteger('signatory_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();

            $table->boolean('is_inactive')->default(false);
            $table->timestamps();

            // Foreign Keys
            $table->foreign('report_id')
                ->references('id')->on('reports')
                ->nullOnDelete();

            $table->foreign('signatory_id')
                ->references('id')->on('signatories')
                ->nullOnDelete();

            $table->foreign('created_by')
                ->references('id')->on('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('report_signatories', function (Blueprint $table) {
            $table->dropForeign(['report_id']);
            $table->dropForeign(['signatory_id']);
            $table->dropForeign(['created_by']);
        });

        Schema::dropIfExists('report_signatories');
    }
};