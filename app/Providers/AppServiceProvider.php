<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use App\Models\Fakultas;
use Illuminate\Pagination\Paginator;

// Models
use App\Models\Notulensi;
use App\Models\KasTransaction;
use App\Models\KasTagihan;

// Policies
use App\Policies\NotulensiPolicy;
use App\Policies\KasTransactionPolicy;
use App\Policies\KasTagihanPolicy;

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
        // ── Policy registrations ─────────────────────────────
        Gate::policy(Notulensi::class,      NotulensiPolicy::class);
        Gate::policy(KasTransaction::class, KasTransactionPolicy::class);
        Gate::policy(KasTagihan::class,     KasTagihanPolicy::class);

        // ── View composers ───────────────────────────────────
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
