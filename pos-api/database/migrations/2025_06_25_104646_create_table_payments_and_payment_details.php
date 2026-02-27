<?php

use App\Enums\PaymentStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_statuses', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('name', 120);
            $table->string('description', 250)->nullable();

            $table->softDeletes();
            $table->timestamps();
        });

        // $statuses = [
        //     [
        //         'name' => 'PENDING',
        //         'description' => ''
        //     ],
        //     [
        //         'name' => 'PAID',
        //         'description' => ''
        //     ]
        // ];

        // Previously inserted statuses here; now managed by PaymentStatusSeeder

        // foreach ($statuses as $status) {
        //     \DB::table('payment_statuses')->insert($status);
        // }

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('ref_no', 20)->unique();
            $table->dateTime('date');
            $table->string('remarks', 250)->nullable();
            $table->foreignId('file_id')->nullable()->constrained('files');
            $table->string('payee_idno');
            $table->foreignId('payment_method_id')->references('id')->on('payment_types')->noActionOnDelete();
            $table->foreignId('cash_bank_id')->references('id')->on('chart_accounts')->noActionOnDelete();

            $table->string('payee_name', 150);
            $table->string('payee_email', 150)->nullable();
            $table->string('payee_address', 150)->nullable();

            $table->decimal("amount", 18, 4);

            $table->foreignId('creator_id')->constrained('users');
            $table->foreignId('status_id')->default(PaymentStatusEnum::PENDING->value)->constrained('payment_statuses');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('payment_details', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('payment_id')->references('id')->on('payments')->noActionOnDelete();
            $table->foreignId('product_id')->references('id')->on('products')->noActionOnDelete();
            $table->string('product_name', 120)->nullable();
            $table->string('product_description', 250)->nullable();
            $table->integer('quantity')->unsigned();
            $table->decimal('rate', 18, 4);
            $table->foreignId('product_expense_id')->references('id')->on('chart_accounts')->noActionOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_details');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('payment_statuses');
    }
};
