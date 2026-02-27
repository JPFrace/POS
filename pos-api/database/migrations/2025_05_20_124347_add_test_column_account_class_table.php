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

        Schema::table('account_classes', function (Blueprint $table) {
            $table->string("test", length: 25)->nullable();
            $table->string("name", length: 200)->change();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('account_classes', function (Blueprint $table) {
            $table->dropColumn(["test"]);
            $table->string("name", length: 150)->change();

        });
    }
};
