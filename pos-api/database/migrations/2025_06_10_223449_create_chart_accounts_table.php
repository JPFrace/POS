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
        Schema::create('chart_accounts', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string("code", 35);
            $table->string("name", 120);
            $table->string("description", 250)->nullable();
            $table->foreignId("type_id")->constrained("account_types");
            $table->foreignId("class_id")->nullable()->constrained("account_classes");
            $table->foreignId("parent_id")->nullable()->constrained("chart_accounts");
            $table->foreignId("dept_id")->nullable()->constrained("departments");
            $table->decimal("balance", 18, 4)->nullable();
            $table->decimal("running_balance", 18, 4)->nullable();
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
        Schema::dropIfExists('chart_accounts');
    }
};
