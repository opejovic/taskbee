<?php

namespace Tests\Feature;

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
        // Arrange: 3 Workspace types Basic, Advanced, Pro
        $basic = factory(Plan::class)->states('basic')->create();
        $advanced = factory(Plan::class)->states('advanced')->create();
        $pro = factory(Plan::class)->states('pro')->create();

        // Act: Customer visits the workspace bundles page.
        $response = $this->get('/bundles');

        // Assert: Customer can see the workspace bundles and the details of each bundle.
        $response->assertStatus(200);
        $response->assertViewIs('plans.index');

        $response->assertSee($basic->name);
        $response->assertSee(5);
        $response->assertSee($basic->amount);

        $response->assertSee($advanced->name);
        $response->assertSee(12);
        $response->assertSee($advanced->amount);

        $response->assertSee($pro->name);
        $response->assertSee(20);
        $response->assertSee($pro->amount);
    }
}
