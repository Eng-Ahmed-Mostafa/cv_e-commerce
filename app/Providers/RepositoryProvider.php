<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Interface\Http\Categories\CategoryInterface::class,
            \App\Interface\Http\Categories\CategoryRepository::class
        );
        $this->app->bind(
            \App\Interface\Api\Categories\CategoryInterface::class,
            \App\Interface\Api\Categories\CategoryRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
