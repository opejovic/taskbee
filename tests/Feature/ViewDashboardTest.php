<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewDashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function unauthenticated_users_cannot_view_dashboard_page()
    {
        $response = $this->get('/dashboard')->assertRedirect('/login');
        
    }

    /** @test */
    function workspace_owners_can_view_dashboard_page()
    {
        $this->withoutExceptionHandling();
        // Arrange: existing workspace and workspace owner
        $owner = factory(User::class)->create();
        $workspace = factory(Workspace::class)->create(['created_by' => $owner->id]);

        // Act: visit /dashboard
        $response = $this->actingAs($owner)->get('/dashboard');

        // Assert: the dashboard is visible
        $response->assertViewIs('dashboards.show');
    }
}
