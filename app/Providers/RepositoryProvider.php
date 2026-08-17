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
        $this->app->bind(
            \App\Interface\Http\Brands\BrandInterface::class,
            \App\Interface\Http\Brands\BrandRepository::class
        );
        $this->app->bind(
            \App\Interface\Api\Brands\BrandInterface::class,
            \App\Interface\Api\Brands\BrandRepository::class
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
