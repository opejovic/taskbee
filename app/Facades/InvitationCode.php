<?php

namespace taskbee\Facades;

use Illuminate\Support\Facades\Facade;
use taskbee\Helpers\InvitationCodeGenerator;

class InvitationCode extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() : string
    {
        return InvitationCodeGenerator::class;
    }
}
