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
        Schema::table('logger', function (Blueprint $table) {
            $table->uuid()->after('id');
            $table->ipAddress();
            $table->string("device");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logger', function (Blueprint $table) {
            $table->dropColumn(['ip_address', 'device', 'uuid']);
        });
    }
};
