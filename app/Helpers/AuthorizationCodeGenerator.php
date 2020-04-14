<?php

namespace taskbee\Helpers;

interface AuthorizationCodeGenerator
{
    /**
     * Generate an authorization code for initial workspace setup.
     */
    public function generate() : string;
}
