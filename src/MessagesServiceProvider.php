<?php

namespace GoomCoom\Messages;

use Illuminate\Support\ServiceProvider;

class MessagesServiceProvider extends ServiceProvider
{

    public function register()
    {
        app()->bind('messages', static function() {
            return new Messages;
        });
    }

    public function boot()
    {
        //
    }
}
