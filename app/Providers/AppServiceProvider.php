<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
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
        //define nav items based on auth status
        View::composer('*', function ($view) {
            if (Auth::check())
            {
                $view->with('nav', [
                    'Tracks' => 'track.index',
                    'Map' => 'track.map',
                    'Days Chart' => 'track.chart',
                    'Upload Tracks' => 'track.create',
                    'About' => 'about',
                ]);
            }
            else
            {
                $view->with('nav', [
                    'Register' => 'register',
                    'Login' => 'login',
                ]);
            }
        });
    }
}
