<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */


    public function run(): void
    {
        // Create a company with the name 'Company'
        Company::firstOrCreate(
            ['name' => 'Company'],
            [
                'address' => '123 Main St, Quezon City, NCR, Philippines',
                'phone' => '123-456-7890',
                'logo' => 'media\\avatars\\blank.png',
                'tin_no' => '123456789',
                'email' => 'example@gmail.com'
            ]
        );
    }
}
