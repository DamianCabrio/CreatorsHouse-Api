<?php

namespace App\Providers;

use App\Mail\UserCreated;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
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

    public function boot()
    {
        Schema::defaultStringLength(191);

        User::created(function ($user) {
            $url = "http://localhost:8080/#/login?token=" . $user->verification_token;
            retry(5, function () use ($user,$url) {
                Mail::to($user)->send(new UserCreated($user,$url));
            }, 100);
        });

        /*        User::updated(function ($user) {
                    if ($user->isDirty("email")) {
                        retry(5, function () use ($user) {
                            Mail::to($user)->send(new UserMailChanged($user));
                        }, 100);
                    }
                });*/
    }
}
