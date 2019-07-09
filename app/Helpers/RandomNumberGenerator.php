<?php

namespace App\Helpers;

class RandomNumberGenerator implements AuthorizationCodeGenerator, InvitationCodeGenerator
{
    /**
     * Generate a random 24 character code from a pool of characters for an invitation.
     *
     * @return string
     */
    public function generate()
    {
        $pool = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        return substr(str_shuffle(str_repeat($pool, 24)), 0, 24);
    }
}
