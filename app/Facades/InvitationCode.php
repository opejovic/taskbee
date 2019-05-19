<?php

namespace App\Facades;

use App\Helpers\InvitationCodeGenerator;
use Illuminate\Support\Facades\Facade;

class InvitationCode extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     */
	protected static function getFacadeAccessor()
	{
		return InvitationCodeGenerator::class;
	}
}