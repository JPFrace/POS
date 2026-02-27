<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Enums\OfficialReceiptStatusEnum;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('official_receipt_statuses')->insert([
            'id' => OfficialReceiptStatusEnum::UNPOSTED->value,
            'uuid' => Str::uuid(),
            'name' => OfficialReceiptStatusEnum::UNPOSTED->label(),
            'description' => OfficialReceiptStatusEnum::UNPOSTED->description(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove UNPOSTED
        DB::table('official_receipt_statuses')
            ->where('id', OfficialReceiptStatusEnum::UNPOSTED->value)
            ->delete();
    }
};
