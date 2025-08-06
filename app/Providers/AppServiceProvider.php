<?php

namespace App\Providers;

use App\Models\Truck;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public const HOME = '/admin/dashboard';
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    

public function boot()
{
    Validator::extend('unique_driver', function ($attribute, $value, $parameters, $validator) {
        return !Truck::where('driver_id', $value)->exists();
    });
}
}
