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
        Schema::create('account_classes', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string("code", length: 75);
            $table->string("name", length: 150)->nullable();
            $table->string("short_name", length: 100)->nullable();
            $table->string("description", length: 250)->nullable();
            $table->boolean("is_inactive")->default(0)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_classes');
    }
};
