<?php

namespace App\Providers;

use App\Models\SubModule;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {

            $modules = User::with(['roles', 'modules.submodules' => function ($query) {
                $query->whereIn('role_id', Auth::user()->roles->pluck('id'));
            }])->find(Auth::user()->id);

            View::share(['modules' => $modules]);
        });
    }
}
