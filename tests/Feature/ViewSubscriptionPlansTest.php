<?php

namespace Tests\Feature;

use Tests\TestCase;
use taskbee\Models\Plan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewSubscriptionPlans extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function customers_can_view_available_subscription_plans()
    {
        $basicPlan = factory(Plan::class)->states('basic')->create();
        $advancedPlan = factory(Plan::class)->states('standard')->create();
        $proPlan = factory(Plan::class)->states('premium')->create();

        $response = $this->get('/plans');

        $response->assertStatus(200);
        $response->assertViewIs('subscription-plans.index');

        $response->assertSee($basicPlan->name);
        $response->assertSee($basicPlan->members_limit);
        $response->assertSee($basicPlan->amountInEur);

        $response->assertSee($advancedPlan->name);
        $response->assertSee($advancedPlan->members_limit);
        $response->assertSee($advancedPlan->amountInEur);

        $response->assertSee($proPlan->name);
        $response->assertSee($proPlan->members_limit);
        $response->assertSee($proPlan->amountInEur);
    }
}
