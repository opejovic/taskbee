<?php 

namespace App\Helpers;

interface AuthorizationCodeGenerator
{
	/**
     * Generate an authorization code for initial workspace setup.
     *
     * @return string
     */
	public function generate();
}
