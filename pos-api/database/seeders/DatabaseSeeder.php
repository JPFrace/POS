<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Policy;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesSeeder::class,
            UsersSeeder::class,
            PaymentTypesSeeder::class,
            AccountCategoriesSeeder::class,
            ContactSubTypesSeeder::class,
            AccountUsageTypesSeeder::class,
            ReportsSeeder::class,
            TransactionTypesSeeder::class,
            PoliciesSeeder::class,
            ContactClassesSeeder::class,
            ConfigurationSeeder::class,
            CountrySeeder::class,
            PaymentStatusSeeder::class,
            TaxonomySeeder::class,
            CreateFinancialCodesSeeder::class,
        ]);
    }
}
