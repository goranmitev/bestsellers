<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

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
        Validator::extend('multipleof20', function ($attribute, $value, $parameters, $validator) {
            return $value % 20 === 0;
        });

        Validator::extend('isbn', function ($attribute, $value, $parameters, $validator) {

            $isbns = explode(';', $value);

            foreach ($isbns as $isbn) {
                if (strlen($isbn) !== 10 && strlen($isbn) !== 13) {
                    return false;
                }
            }

            return true;
        });
    }
}
