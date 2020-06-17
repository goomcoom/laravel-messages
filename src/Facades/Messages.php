<?php

namespace GoomCoom\Messages\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * The messages facade.
 *
 * @method static getBags()
 * @method static hasAny()
 * @method static getAll()
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
    protected static function getFacadeAccessor()
    {
        return 'messages';
    }
}
