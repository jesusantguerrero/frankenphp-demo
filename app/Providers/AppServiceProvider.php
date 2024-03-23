<?php

namespace App\Providers;

use App\Jobs\ProcessVersions;
use App\Services\SiteService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bindMethod([ProcessVersions::class, 'handle'], function (ProcessVersions $job, Application $app) {
            return $job->handle($app->make(SiteService::class));
        });
    }
}
