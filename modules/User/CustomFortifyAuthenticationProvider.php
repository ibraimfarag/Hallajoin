<?php

namespace Modules\User;

use Illuminate\Support\ServiceProvider;

class CustomFortifyAuthenticationProvider extends ServiceProvider
{
    public function boot() {}

    public function register()
    {
        $this->app->bind(\Laravel\Fortify\Http\Requests\LoginRequest::class, \Modules\User\Fortify\LoginRequest::class);
    }
}
