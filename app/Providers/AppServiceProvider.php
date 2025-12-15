<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Models\Category;
use Illuminate\Support\ServiceProvider;

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
        Paginator::useBootstrap();

        View::composer('*', function ($view) {
            // Only get the categories whose names are "Boys" or "Girls"
            $navCategories = Cache::remember('nav.categories', 600, function () {
                return Category::with(['children.children'])
                    ->roots()
                    ->whereIn('name', ['Boys', 'Girls'])  // Filter only "Boys" and "Girls" categories
                    ->orderBy('name')
                    ->get();
            });

            $view->with('navCategories', $navCategories);
        });
    }   
}
