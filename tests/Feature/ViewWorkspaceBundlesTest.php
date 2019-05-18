<?php

namespace Tests\Feature;

use App\Models\Bundle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewWorkspaceBundlesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function customers_can_view_available_workspace_bundles()
    {
        // Arrange: 3 Workspace types Basic, Advanced, Pro
        $basic = factory(Bundle::class)->states('basic')->create();
        $advanced = factory(Bundle::class)->states('advanced')->create();
        $pro = factory(Bundle::class)->states('pro')->create();

        // Act: Customer visits the workspace bundles page.
        $response = $this->get('/bundles');

        // Assert: Customer can see the workspace bundles and the details of each bundle.
        $response->assertStatus(200);
        $response->assertViewIs('bundles.index');

        $response->assertSee($basic->name);
        $response->assertSee($basic->members_limit);
        $response->assertSee($basic->price);

        $response->assertSee($advanced->name);
        $response->assertSee($advanced->members_limit);
        $response->assertSee($advanced->price);

        $response->assertSee($pro->name);
        $response->assertSee($pro->members_limit);
        $response->assertSee($pro->price);
    }
}
