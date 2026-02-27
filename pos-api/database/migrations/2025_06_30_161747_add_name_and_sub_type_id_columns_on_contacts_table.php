<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table("contacts", function (Blueprint $table) {
            $table->string("name", 150)->nullable()->unique();
            $table->foreignId('sub_type_id')->nullable()->constrained('contact_sub_types')->noActionOnDelete()->noActionOnUpdate();

            $table->string('first_name', 75)->nullable()->change();
            $table->string('last_name', 75)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("contacts", function (Blueprint $table) {
            $table->dropColumn("name");
            $table->dropForeign(['sub_type_id']);
        });
    }
};
