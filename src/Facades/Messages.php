<?php

namespace GoomCoom\Messages\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * The messages facade.
 *
 * @method static getBags()
 * @method static hasMessages()
 * @method static allMessages()
 * @method static getBag(string $name)
 * @method static add(string $bag, int|string|array ...$messages)
 */
class Messages extends Facade
{
    /**
     * Return the name of the class that has been registered.
     *
     * @return string
     */
    protected static function getFacadeAccessor() :string
    {
        return 'messages';
    }
}
