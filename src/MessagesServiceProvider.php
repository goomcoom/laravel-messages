<?php

namespace GoomCoom\Messages;

use Illuminate\Support\ServiceProvider;

class MessagesServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/goomcoom-laravel-messages.php', 'goomcoom-laravel-messages'
        );

        app()->bind('messages', static function() {
            return new Messages;
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/goomcoom-laravel-messages.php' => config_path('goomcoom-laravel-messages.php'),
        ], 'goomcoom-laravel-messages');
    }
}
