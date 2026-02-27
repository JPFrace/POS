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
        Schema::create('logger', function (Blueprint $table) {
            $table->id();
            $table->string("page", 45);
            $table->string("action", 45);
            $table->string("path", 255);
            $table->string("method", 10);
            $table->string("uri", 255);
            $table->json("reference");
            $table->json("request");
            $table->unsignedBigInteger("creator_id")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logger');
    }
};
