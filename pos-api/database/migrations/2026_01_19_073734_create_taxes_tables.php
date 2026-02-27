<?php

use App\Enums\Method;
use App\Enums\Months;
use App\Enums\Period;
use App\Enums\TaxTypes;
use App\Models\Tax;
use App\Models\TaxAgency;
use App\Models\WithholdingTax;
use App\Models\WithholdingTaxType;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tax_agencies', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('code', 12);
            $table->string('name', 120);
            $table->string("desc", 250)->nullable();
            $table->timestamps();
        });

        TaxAgency::create([
            'code' => 'BIR',
            'name' => 'Bureau of Internal Revenue',
            'desc' => 'Tax Government Agency'
        ]);

        Schema::create('taxes', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->enum('type', array_map(fn($row) => $row->value, TaxTypes::cases()))->nullable();
            $table->foreignId('tax_agency_id')->constrained("tax_agencies");
            $table->string('code', 12);
            $table->string('name', 35);
            $table->string("description", 150)->nullable();
            $table->enum("rate_type", ['fixed', 'percent'])->nullable()->default('percent')->nullable();

            $table->decimal('rate', 10, 2)->default(0)->comment("VAT");
            $table->foreignId('chart_account_id')->nullable()->constrained("chart_accounts")->comment('Output VAT Account');
            $table->foreignId('parent_id')->nullable()->constrained("taxes");
            $table->foreignId('class_id')->nullable()->constrained("taxes");

            $table->softDeletes();
            $table->timestamps();
        });

        Tax::insert([
            [
                'tax_agency_id' => 1,
                'type' => TaxTypes::VAT->value,
                'code' => 'OV',
                'name' => 'Standard',
                'description' => '12% VAT (Standard / Output VAT)',
                'rate' => 12,
                'chart_account_id' => 106,
            ],
            [
                'tax_agency_id' => 1,
                'type' => TaxTypes::VAT->value,
                'code' => 'ZV',
                'name' => 'Zero-Rated VAT',
                'description' => '0% VAT (Zero-Rated VAT)',
                'rate' => 12,
                'chart_account_id' => 106,
            ],
            [
                'tax_agency_id' => 1,
                'type' => TaxTypes::VAT->value,
                'code' => 'EV',
                'name' => 'VAT-Exempt',
                'description' => 'VAT-Exempt Transactions',
                'rate' => 12,
                'chart_account_id' => 106,
            ]
        ]);

        foreach (WithholdingTax::get() as $tax) {

            $type = Tax::firstOrCreate(['code' => trim($tax->taxType->code)], [
                'tax_agency_id' => 1,
                'name' => $tax->taxType->name,
                'description' => $tax->taxType->description,
                'rate_type' => null
            ]);


            Tax::create([
                'tax_agency_id' => 1,
                'type' => TaxTypes::WTH->value,
                'code' => $tax->code,
                'name' => $tax->code,
                'description' => $tax->description,
                'rate' => $tax->rate,
                'chart_account_id' => 106,
                'class_id' => $type->id,
                'parent_id' => $type->id
            ]);
        }

        Schema::create('tax_setup', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger("calendar_id")->constrained("calendars");
            $table->foreignId("tax_id")->constrained("taxes");
            $table->foreignId("payroll_payable_account_id")->constrained("chart_accounts");
            $table->foreignId("sales_payable_account_id")->constrained("chart_accounts");
            $table->foreignId("income_payable_account_id")->nullable()->constrained("chart_accounts");
            $table->enum('period', array_map(fn($row) => $row->value, Period::cases()));
            $table->enum('start_tax_period', array_map(fn($row) => $row->value, Months::cases()));
            $table->date('start_tax_at');
            $table->enum('reporting_method', array_map(fn($row) => $row->value, Method::cases()));
            $table->string("regno", 35);
            $table->timestamps();
        });

        \DB::table("tax_setup")->insert([
            'calendar_id' => '1',
            'tax_id' => 1,
            'payroll_payable_account_id' => 99,
            'sales_payable_account_id' => 99,
            'income_payable_account_id' => null,
            'start_tax_period' => Months::JANUARY->value,
            'start_tax_at' => Carbon::parse('2025-01-01'),
            'reporting_method' => Method::ACCRUAL->value,
            'regno' => 'ABC-COMPANY'
        ]);

        Schema::table("contacts", function (Blueprint $table) {
            $table->dropConstrainedForeignId("tax_id");
            $table->foreignId("tax_id")->nullable()->constrained("taxes");
        });

        Schema::table("products", function (Blueprint $table) {
            $table->foreignId("sales_tax_id")->nullable()->constrained("taxes")->comment("Tax for sales.");
            $table->foreignId("wth_tax_id")->nullable()->constrained("taxes")->comment("Tax for Purchases/Withholding.");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("contacts", function (Blueprint $table) {
            $table->dropConstrainedForeignId("tax_id");
            $table->foreignId("tax_id")->nullable()->constrained("withholding_taxes");
        });

        Schema::table("products", function (Blueprint $table) {
            if (Schema::hasColumn("products", "sales_tax_id")) {
                $table->dropConstrainedForeignId("sales_tax_id");
            };

            if (Schema::hasColumn("products", "wth_tax_id")) {
                $table->dropConstrainedForeignId("wth_tax_id");
            };
        });

        Schema::dropIfExists('tax_setup');
        Schema::dropIfExists('taxes');
        Schema::dropIfExists('tax_agencies');

    }
};
