<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\View;
use App\Models\Fakultas;
use Illuminate\Pagination\Paginator;

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
        View::composer('*', function ($view) {
            if (auth()->check()) {
                $view->with('currentUser', auth()->user());
            }
        });

        if (\Illuminate\Support\Facades\Schema::hasTable('fakultas')) {
            View::share('fstId', \App\Models\Fakultas::where('nama_fakultas', 'like', '%Sains%')->value('id'));
            View::share('fisId', \App\Models\Fakultas::where('nama_fakultas', 'like', '%Sosial%')->value('id'));
        }
    }
}
