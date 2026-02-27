<?php

use App\Enums\NormalBalance;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('account_categories', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('name', 35);
            $table->string("description")->nullable();
            $table->enum(
                'normal_balance',
                array_map(fn($balance) => $balance->name, NormalBalance::cases())
            );
            $table->unsignedInteger("seq")->default(0)->nullable();
            $table->boolean('is_inactive')->default(false)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('account_types', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('name', 75);
            $table->string("description")->nullable();
            $table->foreignId("category_id")->constrained("account_categories");
            $table->boolean('is_inactive')->default(false)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_types');
        Schema::dropIfExists('account_categories');
    }
};
