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
        Schema::disableForeignKeyConstraints();
        Schema::create('config', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('slug', 50)->unique();
            $table->enum('type', ['string', 'integer', 'boolean', 'json:single', 'json:multi', 'none'])->default('none');
            $table->text('options')->nullable();
            $table->text('value')->nullable();
            $table->foreignId('parent_id')->nullable()->nullOnDelete();
            $table->boolean('use_prefix')->default(false);
            $table->string('prefix', 50)->nullable();
            $table->boolean('use_suffix')->default(false);
            $table->string('suffix', 50)->nullable();
            $table->boolean('is_inactive')->default(false);
            $table->timestamps();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('config');
        Schema::enableForeignKeyConstraints();
    }
};
