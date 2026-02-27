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
            $table->integer('type')->nullable()->change();
            $table->string('middle_name', 75)->nullable()->change();
            $table->string('suffix', 5)->nullable()->change();
            $table->string('email', 100)->nullable()->change();
            $table->string('billing_address', 500)->nullable()->change();
            $table->string('address', 500)->nullable()->change();
            $table->string('country', 75)->nullable()->change();
            $table->integer('zip_code')->nullable()->change();
            $table->string('contact_number', 15)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
