<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Enums\JournalEntryStatusEnum;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('journal_entry_statuses')
            ->where('id', 6)
            ->update(['id' => JournalEntryStatusEnum::VOIDED->value]);

        DB::table('journal_entry_statuses')->insert([
            'id' => JournalEntryStatusEnum::UNPOSTED->value,
            'uuid' => Str::uuid(),
            'name' => JournalEntryStatusEnum::UNPOSTED->label(),
            'description' => JournalEntryStatusEnum::UNPOSTED->description(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove UNPOSTED
        DB::table('journal_entry_statuses')
            ->where('id', JournalEntryStatusEnum::UNPOSTED->value)
            ->delete();

        // Revert VOIDED from 7 back to 6
        DB::table('journal_entry_statuses')
            ->where('id', JournalEntryStatusEnum::VOIDED->value)
            ->update(['id' => 6]);
    }
};
