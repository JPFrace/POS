<?php

namespace Tests\Unit;

use App\Supports\Utils\Url;
use Illuminate\Support\Facades\Http;

class ExampleTest extends \Tests\TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }

    public function test_api()
    {
        \Log::info(config('system.is_access_token'));
        \Log::info(Url::ISbaseUrl("/users/basic-info"));
        $response = Http::withHeader("Accept", "application/json")
            ->withToken(config('system.is_access_token'))
            ->get(Url::ISbaseUrl("/users/basic-info"), [
                'email' => 'victortagupa@gmail.com'
            ]);

        \Log::info($response->json());
        \Log::info($response);
    }
}
