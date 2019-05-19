<?php

namespace Tests\Feature;

use App\Models\Bundle;
use App\Models\Plan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewWorkspaceBundlesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function customers_can_view_available_workspace_bundles()
    {
        $this->withoutExceptionHandling();
        $basicBundle = factory(Bundle::class)->states('basic')->create(['stripe_id' => 'prod_BSCID123']);
        $advancedBundle = factory(Bundle::class)->states('advanced')->create(['stripe_id' => 'prod_ADVID123']);
        $proBundle = factory(Bundle::class)->states('pro')->create(['stripe_id' => 'prod_PROID123']);

        $basicPlan = factory(Plan::class)->states('basic')->create(['product' => $basicBundle->stripe_id]);
        $advancedPlan = factory(Plan::class)->states('advanced')->create(['product' => $advancedBundle->stripe_id]);
        $proPlan = factory(Plan::class)->states('pro')->create(['product' => $proBundle->stripe_id]);

        // Act: Customer visits the workspace bundles page.
        $response = $this->get('/bundles');

        // Assert: Customer can see the workspace bundles and the details of each bundle.
        $response->assertStatus(200);
        $response->assertViewIs('subscription-plans.index');

        $response->assertSee($basicPlan->bundle->name);
        $response->assertSee($basicPlan->bundle->members_limit);
        $response->assertSee($basicPlan->amount);

        $response->assertSee($advancedPlan->bundle->name);
        $response->assertSee($advancedPlan->bundle->members_limit);
        $response->assertSee($advancedPlan->amount);

        $response->assertSee($proPlan->bundle->name);
        $response->assertSee($proPlan->bundle->members_limit);
        $response->assertSee($proPlan->amount);
    }
}
