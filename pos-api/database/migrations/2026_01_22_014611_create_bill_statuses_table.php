<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bill_statuses', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->string('name', 50)->unique();
            $table->text('description')->nullable();
        });

        foreach (\App\Enums\BillStatusEnum::cases() as $status) {
            DB::table('bill_statuses')->insert([
                'id' => $status->value,
                'uuid' => Str::uuid(),
                'name' => $status->label(),
                'description' => $status->description(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_statuses');
    }
};
