<?php

namespace Tests\Unit;

use App\Models\Plan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PlanTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	function can_assemble_itself()
	{
		$this->assertCount(0, Plan::all());
		$plans = $this->getPlans();

		Plan::assemble($plans);

		$this->assertCount(4, Plan::all());
	}

	private function getPlans()
	{
		$basic = [ 
			"id" => "plan_F8k6u8imre8C85",
			"amount" => 3995,
			"currency" => "eur",
			"interval" => "month",
			"metadata" => [
				"members_limit" => "5",
			],
			"nickname" => "Basic Monthly",
		];

		$standard = [ 
			"id" => "plan_faas8d8271728sd",
			"amount" => 6995,
			"currency" => "eur",
			"interval" => "month",
			"metadata" => [
				"members_limit" => "5",
			],
			"nickname" => "Standard Monthly",
		];

		$premium = [ 
			"id" => "plan_imre8C85F8k6u8",
			"amount" => 9995,
			"currency" => "eur",
			"interval" => "month",
			"metadata" => [
				"members_limit" => "5",
			],
			"nickname" => "Premium Monthly",
		];

		$perUser = [ 
			"id" => "plan_6uke8C8588imFr",
			"amount" => 799,
			"currency" => "eur",
			"interval" => "month",
			"metadata" => [
				"members_limit" => "1",
			],
			"nickname" => "Per User Monthly",
		];

		return collect([$basic, $standard, $premium, $perUser]);
	}
}
