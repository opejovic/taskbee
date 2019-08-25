<?php

namespace taskbee\Helpers;

interface InvitationCodeGenerator
{
    /**
     * Generate an invitation code.
     *
     * @return string
     */
    public function generate();
}
