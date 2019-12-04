<?php

namespace Tests\Feature;

use Tests\TestCase;
use taskbee\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfilesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_users_can_view_their_profile_page()
    {
        // Arrange: authenticated user
        $user = factory(User::class)->create([
            'first_name' => 'John',
            'last_name' => 'Doe'
        ]);

        // Act: visits their profile page
        $response = $this->actingAs($user)->get("/profiles/{$user->id}");

        // Assert: sees the profile page
        $response->assertSee('John Doe');
    }
}
