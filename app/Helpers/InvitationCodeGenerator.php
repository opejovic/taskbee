<?php

namespace taskbee\Helpers;

interface InvitationCodeGenerator
{
    /**
     * Generate an invitation code.
     */
    public function generate() : string;
}
