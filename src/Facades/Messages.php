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
 * @method static add(string $bag, string ...$messages)
 * @method static remove(string $string, string ...$messages)
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
