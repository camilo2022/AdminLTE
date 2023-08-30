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
            //Verificamos si el usuario esta registrado
            if (auth()->check()) {
                $user = User::find(Auth::id());
                $modules = $user->modules->sortBy('id')->values()->all(); 
                foreach ($modules as $module) {
                    $subModulesJson = $module->pivot->sub_modules;
                    $subModules = json_decode($subModulesJson);
                    $module->pivot->sub_modules = $subModules;
                    $SubModules = SubModule::whereIn('id', $subModules)->where('is_active', true)->get();
                    $module->SubModules = $SubModules;

                }
                View::share(['modules' => $modules]);

            }
        });
    }
}
