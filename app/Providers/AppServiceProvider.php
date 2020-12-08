<?php

namespace App\Providers;

use App\Mail\PostNotification;
use App\Mail\UserCreated;
use App\Models\Creator;
use App\Models\Follow;
use App\Models\Post;
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
            retry(5, function () use ($user) {
                Mail::to($user)->send(new UserCreated($user));
            }, 100);
        });

        Post::created(function ($post) {
            $creator = Creator::where("id", $post->idCreator)->firstOrFail();
            $followers = Follow::where("idCreator", $post->idCreator)->get();
            $emails = [];
            foreach ($followers as $follower) {
                if ($post->isPublic == 1) {
                    $user = User::where("id", $follower->idUser)->firstOrFail();
                    $email = $user->email;
                    array_push($emails, $email);
                } else if ($post->public == 0 && $follower->isVip == 1) {
                    $user = User::where("id", $follower->idUser)->firstOrFail();
                    $email = $user->email;
                    array_push($emails, $email);
                }
            }

            retry(5, function () use ($emails, $post, $creator) {
                Mail::to($emails)->send(new PostNotification($post, $creator));
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
