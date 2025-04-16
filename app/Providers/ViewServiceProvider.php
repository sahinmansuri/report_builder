<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
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
        View::composer(
            ['components.radio', 'components.checkbox', 'components.select'],
            'App\Http\View\Composers\OptionComposer'
        );

        View::composer(
            ['components.complex'],
            'App\Http\View\Composers\ComplexComposer'
        );
    }
}
