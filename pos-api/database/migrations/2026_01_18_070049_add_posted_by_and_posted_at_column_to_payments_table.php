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
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('posted_by')
                ->nullable()
                ->after('status_id')
                ->constrained('users')
                ->restrictOnDelete();
            $table->dateTime('posted_at')->nullable()->after('posted_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['posted_by']);
            $table->dropColumn('posted_by');
            $table->dropColumn('posted_at');
        });
    }
};
