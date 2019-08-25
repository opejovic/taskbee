<?php

namespace taskbee\Facades;

use Illuminate\Support\Facades\Facade;
use taskbee\Helpers\AuthorizationCodeGenerator;

class AuthorizationCode extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return AuthorizationCodeGenerator::class;
    }
}
