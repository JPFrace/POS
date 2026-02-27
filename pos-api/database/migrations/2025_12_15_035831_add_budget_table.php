<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Symfony\Polyfill\Uuid\Uuid;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('budget_types', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('name', 100);
            $table->string('description', 250)->nullable();
            $table->boolean('is_inactive')->default(false);
            $table->foreignId('creator_id')->constrained('users');
            $table->timestamps();
        });

        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('name', 120);
            $table->string('description', 250)->nullable();
            $table->foreignId('department_id')->nullable()->constrained('departments');
            $table->foreignId('calendar_id')->nullable()->constrained('calendars');
            $table->foreignId('type_id')->nullable()->constrained('budget_types');
            $table->boolean('is_inactive')->default(false);
            $table->foreignId('creator_id')->constrained('users');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('budget_details', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('budget_id')->constrained('budgets');
            $table->foreignId('chart_account_id')->constrained('chart_accounts');
            $table->foreignId('product_id')->constrained('products');
            $table->string('name', 120)->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('description', 250)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        DB::table('budget_types')->insert([
            ['uuid' => Str::uuid(), 'name' => 'CAPEX', 'description' => 'Capital expenditure budget', 'creator_id' => 1, 'created_at' => now()],
            ['uuid' => Str::uuid(), 'name' => 'OPEX', 'description' => 'Operational expenditure budget', 'creator_id' => 1, 'created_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_details');
        Schema::dropIfExists('budgets');
        Schema::dropIfExists('budget_types');
    }
};
