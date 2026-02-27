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
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('je_no', 25);
            $table->string('ref_no', 25);
            $table->datetime('date');
            $table->string('memo', 250)->nullable();
            $table->foreignId('file_id')->nullable()->constrained('files');
            $table->foreignId('creator_id')->constrained('users');
            $table->dateTime("posted_at")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('journal_details', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('entry_id')->constrained('journal_entries');
            $table->foreignId('chart_account_id')->constrained('chart_accounts');
            $table->foreignId('dept_id')->nullable()->constrained('chart_accounts');
            $table->decimal('debit', 18, 4)->nullable();
            $table->decimal('credit', 18, 4)->nullable();
            $table->string('name', 120)->nullable();
            $table->string('description', 250)->nullable();
            $table->string('contact_name')->nullable();
            $table->string("contact_type")->nullable();
            $table->string("contact_idno")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('transaction_types', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('code', 15);
            $table->string('name', 75);
            $table->string('description', 250)->nullable();
            $table->boolean('is_inactive')->default(false)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('journals', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('trans_type', 15);
            $table->string('ref_no', 25);
            $table->foreignId('chart_account_id')->constrained('chart_accounts');
            $table->foreignId('dept_id')->nullable()->constrained('departments');
            $table->decimal('debit', 18, 4);
            $table->decimal('credit', 18, 4);
            $table->string('description', 250)->nullable();
            $table->string('contact_name')->nullable();
            $table->string("contact_type")->nullable();
            $table->string("contact_idno")->nullable();
            $table->foreignId('creator_id')->constrained('users');
            $table->dateTime('posted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_details');
        Schema::dropIfExists('journal_entries');
        Schema::dropIfExists('transaction_types');
        Schema::dropIfExists('journals');
    }
};
