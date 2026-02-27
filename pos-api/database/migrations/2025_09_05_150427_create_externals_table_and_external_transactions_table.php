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
        Schema::table('financial_trans_codes', function (Blueprint $table) {
            $table->string('code', 25)->unique()->change();
        });

        Schema::table('chart_accounts', function (Blueprint $table) {
            $table->string('code', 35)->unique()->change();
        });

        Schema::create('externals', function (Blueprint $table) {
            $table->id();
            $table->uuid();

            $table->foreignId('user_id')->references('id')->on('users');
            $table->string('trans_type', 25);
            $table->foreign('trans_type')->references('code')->on('financial_trans_codes');
            $table->string('remarks', 250)->nullable();

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('external_transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid();

            $table->foreignId('header_id')->references('id')->on('externals');
            $table->string('code', 35);
            $table->decimal('credit', 18, 4);
            $table->decimal('debit', 18, 4);
            $table->dateTime('document_date');
            $table->string('cost_center');
            $table->foreign('cost_center')->references('code')->on('departments');
            $table->string('contact_name', 120)->nullable();
            $table->string('contact_id_no', 10)->nullable();
            $table->foreign('contact_id_no')->references('id_no')->on('contacts');
            $table->string('reference_no', 25)->nullable();
            $table->string('remarks', 75)->nullable();
            $table->string('account', 75);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_transactions');
        Schema::dropIfExists('externals');
        Schema::table('financial_trans_codes', function (Blueprint $table) {
            $table->dropUnique(['code']);
        });
        Schema::table('chart_accounts', function (Blueprint $table) {
            $table->dropUnique(['code']);
        });
    }
};
