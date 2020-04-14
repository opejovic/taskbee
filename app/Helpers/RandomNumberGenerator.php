<?php

namespace taskbee\Helpers;

class RandomNumberGenerator implements AuthorizationCodeGenerator, InvitationCodeGenerator
{
    /**
     * @var string
     */
    protected $pool = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

    /**
     * Generate a random 24 character code from a pool of characters.
     */
    public function generate() : string
    {

        return substr(str_shuffle(str_repeat($this->pool, 24)), 0, 24);
    }
}
