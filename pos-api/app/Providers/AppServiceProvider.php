<?php

namespace App\Providers;

use App\Models\OfficialReceipt;
use App\Repositories\OfficialReceiptRepository;
use App\Repositories\PostRepository;
use App\Applications\Transactions\OfficialReceiptService;
use App\Supports\Utils\ReferenceNumb;
use App\Supports\Utils\SystemConfig;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('systemconfig', function ($app) {
            return new SystemConfig();
        });

        $this->app->singleton('referencenumb', function () {
            return new ReferenceNumb();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(OfficialReceiptService::class, function () {
            return new OfficialReceiptService(
                Auth::user(),
                app(OfficialReceiptRepository::class)
            );
        });
    }
}
