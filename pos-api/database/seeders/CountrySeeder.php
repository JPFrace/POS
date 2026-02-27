<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Country::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $response = Http::get('https://restcountries.com/v3.1/all?fields=name,flags,cca2');

        if ($response->failed()) {
            $this->command->error('Failed to fetch countries from API');
            return;
        }

        $countries = collect($response->json())
            ->map(fn($country) => [
                'name' => $country['name']['common'],
                'code' => $country['cca2'],
                'flag' => $country['flags']['svg'] ?? $country['flags']['png'] ?? null,
            ])
            ->sortBy('name')
            ->values();

        $philippines = $countries->firstWhere('name', 'Philippines');
        if ($philippines) {
            $countries = $countries->reject(fn($c) => $c['name'] === 'Philippines')
                ->prepend($philippines);
        }

        $now = now();
        $data = $countries->map(fn($country) => [
            'uuid' => Str::uuid(),
            'name' => $country['name'],
            'code' => $country['code'],
            'flag' => $country['flag'],
            'created_at' => $now,
            'updated_at' => $now,
        ])->toArray();

        Country::insert($data);

        $this->command->info('Countries seeded successfully ' . count($data) . ' countries with Philippines at the top!');
    }
}
