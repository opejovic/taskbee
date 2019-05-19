<?php

namespace App\Helpers;

interface InvitationCodeGenerator
{	
	/**
     * Generate a code for an invitation.
     *
     * @return string
     */
	public function generate();
}