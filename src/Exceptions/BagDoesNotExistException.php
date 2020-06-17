<?php

namespace GoomCoom\Messages\Exceptions;

use Exception;
use Throwable;

class BagDoesNotExistException extends Exception
{
    public function __construct($bag, $code = 0, Throwable $previous = null)
    {
        $message = "The bag \"${bag}\" does not exist. You may edit the available message bags via the config.";
        parent::__construct($message, $code, $previous);
    }
}
