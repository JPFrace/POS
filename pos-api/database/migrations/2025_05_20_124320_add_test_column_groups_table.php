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
        Schema::table("groups", function (Blueprint $table) {
            $table->string("test", 25)->nullable();
            $table->string("name", 120)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("groups", function (Blueprint $table) {
            $table->dropColumn(["test"]);
            $table->string("name", 75)->change();
        });
    }
};
