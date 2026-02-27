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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('id_no', 10)->unique();
            $table->integer('type')->nullable();
            $table->string('first_name', 75);
            $table->string('last_name', 75);
            $table->string('middle_name', 75)->nullable();
            $table->string('suffix', 5)->nullable();
            $table->string('email', 100)->unique()->nullable();
            $table->string('billing_address', 500)->nullable();
            $table->string('address', 500)->nullable();
            $table->string('country', 75)->nullable();
            $table->integer('zip_code')->nullable();
            $table->string('contact_number', 15)->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('contact_details', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('contact_id')->constrained('contacts')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name', 75);
            $table->string('contact_number', 15);
            $table->string('address', 500)->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_details');
        Schema::dropIfExists('contacts');
        Schema::dropIfExists('contact_sub_types');
    }
};
