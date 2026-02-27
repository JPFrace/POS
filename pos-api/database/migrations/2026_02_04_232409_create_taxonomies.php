<?php

use App\Models\Bill;
use App\Models\BillStatus;
use App\Models\Deposit;
use App\Models\Invoice;
use App\Models\InvoiceStatus;
use App\Models\OfficialReceipt;
use App\Models\OfficialReceiptStatus;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentStatus;
use App\Models\Taxonomy;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('taxonomy', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string("name", 70);
            $table->json("tags");
            $table->string("description", 150)->nullable();
            $table->timestamps();
        });

        Artisan::call("db:seed --class=TaxonomySeeder");

        OfficialReceipt::withTrashed()->whereNotIn('status_id', [1, 2])->update([
            'status_id' => 1
        ]);

        Invoice::withTrashed()->whereNotIn('status_id', [1, 2])->update([
            'status_id' => 1
        ]);

        Bill::withTrashed()->whereNotIn('status_id', [1, 2])->update([
            'status_id' => 1
        ]);

        Payment::withTrashed()->whereNotIn('status_id', [1, 2])->update([
            'status_id' => 1
        ]);

        Schema::table("official_receipts", function (Blueprint $table) {
            $table->dropForeignIdFor(OfficialReceiptStatus::class, 'status_id');
            $table->foreign("status_id")->nullable()->references("id")->on("taxonomy");
        });

        Schema::table("payments", function (Blueprint $table) {
            $table->dropForeign('payments_ibfk_5');
        });

        Schema::table("invoices", function (Blueprint $table) {
            $table->dropForeignIdFor(InvoiceStatus::class, 'status_id');
            $table->foreign("status_id")->nullable()->references("id")->on("taxonomy");
        });

        Schema::table("bills", function (Blueprint $table) {
            $table->dropForeignIdFor(BillStatus::class, 'status_id');
            $table->foreign("status_id")->nullable()->references("id")->on("taxonomy");
        });

        Schema::table("deposits", function (Blueprint $table) {
            $table->foreignId("status_id")->nullable()->references("id")->on("taxonomy");
        });


        Deposit::withTrashed()->whereNotIn('status_id', [1, 2])->update([
            'status_id' => 1
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("official_receipts", function (Blueprint $table) {
            $table->dropForeignIdFor(Taxonomy::class, 'status_id');
            $table->foreign("status_id")->references("id")->on("official_receipt_statuses");
        });

        Schema::table("payments", function (Blueprint $table) {
            $table->dropColumn('status_id');
        });

        Schema::table("payments", function (Blueprint $table) {
            $table->foreignId('status_id')->nullable()->references('id', 'payments_ibfk_5')->on('payment_statuses');
        });

        Schema::table("invoices", function (Blueprint $table) {
            $table->dropForeignIdFor(Taxonomy::class, 'status_id');
            $table->foreign("status_id")->references("id")->on("invoice_statuses");
        });

        Schema::table("bills", function (Blueprint $table) {
            $table->dropForeignIdFor(Taxonomy::class, 'status_id');
            $table->foreign("status_id")->references("id")->on("bill_statuses");
        });

        Schema::table("deposits", function (Blueprint $table) {
            $table->dropConstrainedForeignId("status_id");
        });

        Schema::dropIfExists('taxonomy');
    }
};
