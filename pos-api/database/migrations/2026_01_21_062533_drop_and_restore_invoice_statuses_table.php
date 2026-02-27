<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Clear the table safely without dropping it
        // DB::table('invoice_statuses')->delete();

        // Re-seed from enum
        // foreach (\App\Enums\InvoiceStatusEnum::cases() as $status) {
        //     DB::table('invoice_statuses')->insert([
        //         'id' => $status->value,
        //         'uuid' => Str::uuid(),
        //         'name' => $status->label(),
        //         'description' => $status->description(),
        //     ]);
        // }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback = delete rows, table remains
        // DB::table('invoice_statuses')->delete();
    }
};
