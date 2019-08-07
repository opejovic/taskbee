<?php

namespace taskbee\Facades;

use taskbee\Helpers\AuthorizationCodeGenerator;
use Illuminate\Support\Facades\Facade;

class AuthorizationCode extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     */
    protected static function getFacadeAccessor()
    {
        return AuthorizationCodeGenerator::class;
    }
}
