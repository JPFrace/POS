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
        Schema::table('journals', function (Blueprint $table) {
            $table->foreignId('unposted_by')
                ->nullable()
                ->after('posted_at')
                ->constrained('users')
                ->restrictOnDelete();
            $table->timestamp('unposted_at')->nullable()->after('unposted_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('journals', function (Blueprint $table) {
            $table->dropForeign(['unposted_by']);
            $table->dropColumn('unposted_by');
            $table->dropColumn('unposted_at');
        });
    }
};
