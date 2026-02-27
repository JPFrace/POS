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
        Schema::table('payment_statuses', function (Blueprint $table) {
            $table->string('name', 50)->unique()->change();
            $table->text('description')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_statuses', function (Blueprint $table) {
            $table->dropUnique(['name']);
            $table->string('description', 250)->nullable()->change();
        });
    }
};